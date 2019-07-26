import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import Echo from "laravel-echo";
import Socketio from "socket.io-client";
import {ToastContainer, toast} from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
// minified version is also included
// import 'react-toastify/dist/ReactToastify.min.css';

const portalRoot = document.getElementById("cats");

class Portal extends React.Component {
    constructor(props) {
        super(props);
        this.el = document.createElement("div");

        // this.doKanga = this.handleKanga.bind(this);
    }

    componentDidMount = () => {
        portalRoot.appendChild(this.el);
    };

    componentWillUnmount = () => {
        portalRoot.removeChild(this.el);
    };

    render() {
        const {children} = this.props;
        return ReactDOM.createPortal(children, this.el);
    }
}

export default class Proposals extends Component {
    constructor(props) {
        super(props);
        this.state = {
            'items': [],
            'categories': []
        }
        this.echo = null;
        this.onClick = this.handleClick.bind(this);
    }

    handleClick(event) {
        console.log(event);
    }

    /**
     * Pop a message box up
     *
     * @param msgText string 'Koko is rolling on the floor, I wom=nder why.
     * @param msgType string i.e. infp, success, warning, error
     * @param ttl
     */
    notify(msgText, msgType = 'info', ttl = 4000) {
        toast(msgText, {
                type: msgType,
                hideProgressBar: true,
                autoClose: ttl
            }
        );
    }

    handleVote(item, ctx) {
        // this.setState({item:item})
        console.log('handleVote', ctx, item.id, window.Laravel);
        fetch('/api/vote/', {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            contentType: "application/json; charset=utf-8",
            body: JSON.stringify({
                "proposal_id": item.id,
                "direction": ctx,
                "user": window.Laravel
            })
        }).then(results => results.json())
            .then(results => this.handleResponse(results))
            .catch(err => console.log(err));
    }

    handleResponse(results) {
        this.getProposals();
        this.notify(results.message, results.type, 2000);
        console.log(results);
    }

    componentDidMount() {
        // document.addEventListener('click', this.handleClickOutside, true);
        this.getProposals();
        this.getCategories();
        if (window.keepalive) {
            this.setupSocket();
        }
    }

    componentWillUnmount() {
        // document.removeEventListener('click', this.handleClickOutside, true);
        this.echo.disconnect();
    }

    handleClickOutside = event => {
        console.log(event);
        /*
                const domNode = ReactDOM.findDOMNode(this);

                if (!domNode || !domNode.contains(event.target)) {
                    this.setState({
                        visible: false
                    });
                }
        */
    }

    setupSocket() {
        console.log('Setting up socket.io');

        console.log('Hostname set to ' + window.location.hostname + ':6001');
        this.echo = new Echo({
            broadcaster: 'socket.io',
            client: Socketio,
            host: 'https://' + window.location.hostname + ':6001/'
        });

        console.log('About to set to listening to "messages" for "NewMessage"');
        this.echo.channel('6_acts_database_messages')
            .listen('.NewMessage', (e) => {
                console.log('Message received');
                if (e.hasOwnProperty("politburo")) {
                    this.notify(e.message, e.type, 1000);
                }
                console.log(e);
                if (e.message == 'refresh') {
                    this.getProposals();
                } else {
                    console.log("Unexpected message: " + e.message)
                }
            });
    }

    getProposals() {
        let proto = window.location.protocol + '//';
        let hostName = window.location.hostname;
        fetch(proto + hostName + '/api/proposals', {
                crossDomain: true,
            }
        )
            .then(results => results.json())
            .then(results => this.setState({'items': results}))
    }

    getCategories() {
        let proto = window.location.protocol + '//';
        let hostName = window.location.hostname;
        fetch(proto + hostName + '/api/categories?addSelected=1', {
                crossDomain: true,
            }
        )
            .then(results => results.json())
            .then(results => this.setState({'categories': results}))
    }

    render() {
        return (
            <div>
                <React.Fragment>
                    <Portal>
                        <ul className={"categoriesList"}>
                            {this.state.categories.map((cat, index) => {
                                    return (
                                        <li key={`cat_${cat.id}`}>
                                            <span onClick={this.onClick}
                                                className={`tag ${cat.class} ${cat.sub_class}`}
                                            >
                                                {cat.short_title}
                                        </span>
                                            {cat.selected == 0
                                                ? ''
                                                : 1
                                            }

                                        </li>
                                    )
                                }
                            )}

                        </ul>
                    </Portal>
                </React.Fragment>
                <ul>
                    {this.state.items.map((item, index) => {
                            return (
                                <div key={index} className="u-mtop-2">
                                    <span className="subtitle has-text-weight-bold">{item.title}</span>
                                    <span
                                        className={
                                            `tag is-small u-mleft-15
                                        ${item.category.class}
                                        ${item.category.sub_class}`
                                        }
                                    >
                                {item.category.short_title.substr(0, 1)}
                                </span>
                                    <div className="expander">
                                        {letsDisco(item.body)}&nbsp;&nbsp;
                                        <span className="icon">
                                        <i className="fa fa-plus"></i>
                                    </span>
                                    </div>
                                    <div className="expandable collapsed">
                                        {letsTango(item.body)}
                                    </div>
                                    <div className="author controls u-mtop-10 u-mright-10">
                                        <i>Posted by</i>:&nbsp;
                                        <b>
                                            {item.user.display_name != ''
                                                ? item.user.display_name
                                                : item.user.name
                                            }</b>
                                        &nbsp;
                                    </div>
                                    <div className="aggs controls u-mbottom-20">
                                    <span className="numVotes">
                                    {item.aggs.length > 0 ? item.aggs[0].total_votes : ' No'}</span> votes
                                        <span className="icon u-mleft-20">
                                        <a onClick={() => this.handleVote(item, 'up')}>
                                            <i className="fa fa-arrow-alt-circle-up">&nbsp;</i>
                                        </a>
                                    </span>
                                        <span className="icon">
                                        <a onClick={() => this.handleVote(item, 'down')}>
                                            <i className="fa fa-arrow-alt-circle-down">&nbsp;</i>
                                        </a>
                                    </span>
                                    </div>

                                </div>
                            )
                        }
                    )}
                </ul>
                <ToastContainer/>
            </div>
        );
    }
}

if (document.getElementById('proposals')) {
    ReactDOM.render(<Proposals/>, document.getElementById('proposals'));
}

function letsDisco(theText, theSize) {
    let ret = theText.replace(/^(.{222}[^\s]*).*/, "$1");
    return ret;
};

function letsTango(theText, theSize) {
    let promptLine = theText.replace(/^(.{82}[^\s]*).*/, "$1");
    return _.replace(theText, promptLine, '');
};

