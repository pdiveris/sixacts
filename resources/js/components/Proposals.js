import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import Echo from "laravel-echo";
import Socketio from "socket.io-client";
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
// minified version is also included
// import 'react-toastify/dist/ReactToastify.min.css';

export default class Proposals extends Component {
    constructor(props) {
        super(props);
        this.state = {
            'items': []
        }
        this.echo = null;
    }

    /**
     * Pop a message box up
     *
     * @param msgText string
     * @param msgType string i.e. infp, success, warning, error
     * @param ttl
     */
    notify(msgText, msgType = 'info', ttl = 2000) {
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
        console.log(results);
/*

        if(results.hasOwnProperty('warning')) {
            console.log('I am indeed here in the WARNING!');
            NotificationManager.success('Warning message', results.warning);
        } else if (results.hasOwnProperty('error')) {
            NotificationManager.success('Error message', results.error);
        } else if (results.hasOwnProperty('success')) {
            NotificationManager.success('Success message', results.success);
        }
*/
    }

    componentDidMount() {
        this.getProposals();
        this.setupSocket();
    }

    componentWillUnmount() {
        this.echo.disconnect();
    }

    setupSocket() {
        console.log('Setting up socket.io');

        console.log('Hostname set to '+ window.location.hostname + ':6001');
        this.echo = new Echo({
            broadcaster: 'socket.io',
            client: Socketio,
            host: 'https://'+window.location.hostname + ':6001/'
        });

        console.log('About to set to listening to "messages" for "NewMessage"');
        this.echo.channel('6_acts_database_messages')
            .listen('.NewMessage', (e) => {
                console.log('Message received');

                this.notify(e.message, 'info');

                console.log(e);

                if (e.message == 'refresh') {
                    this.getProposals();
                } else {
                    console.log("Unexpected message: "+e.message)
                }
            });
    }

    getProposals() {
        let proto = window.location.protocol+'//';
        let hostName = window.location.hostname;
        fetch(proto+hostName+'/api/proposals', {
                crossDomain: true,
            }
        )
            .then(results => results.json())
            .then(results => this.setState({'items': results}))
    }


    render() {
        return (
            <div>
                <ul>
                    {this.state.items.map( (item, index) => {
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
                <ToastContainer />
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

