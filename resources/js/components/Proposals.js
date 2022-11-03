import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router, Route, Link } from "react-router-dom";

import Echo from "laravel-echo";
import Socketio from "socket.io-client";
import {ToastContainer, toast} from 'react-toastify';
import Modal from 'react-modal';
import Countdown from 'react-countdown-now';
import 'react-toastify/dist/ReactToastify.css';
// minified version is also included
// import 'react-toastify/dist/ReactToastify.min.css';
import SplashPortal from './Splash';
import Categories from './Categories';
import Filters from './Filters';
import NchanSubscriber from "nchan";

const portalRoot = document.getElementById("cats");
const splashPortalRoot = document.getElementById("splash");

// here was the customStyles

if (document.getElementById('proposals')) {
    // ReactDOM.render(<Proposals/>, document.getElementById('proposals'));
    Modal.setAppElement('#proposals');
}

class Portal extends React.Component {
    constructor(props) {
        super(props);
        this.el = document.createElement("div");
    }

    componentDidMount = () => {
        window.react = '1';
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

// Here was the SplashPortal

export default class Proposals extends Component {
    constructor(props) {
        super(props);
        this.state = {
            'items': [],
            'categories': [],
            'filter': '',
            'query': ''
        }
        this.echo = null;
        this.handleChange = this.handleChange.bind(this);
        this.getCategoriesUpdateFromChild = this.getCategoriesUpdateFromChild.bind(this);
        this.getFiltersUpdateFromChild = this.getFiltersUpdateFromChild.bind(this);
    }

    /**
     * Pop a message box up
     *
     * @param msgText string 'Koko is rolling on the floor, I wonder why.
     * @param msgType string i.e. info, success, warning, error
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

    handleExpand(index, display, elId) {
        const { items } = this.state;
        items[index].display = display;
        // update state
        this.setState({
            items,
        });
    }

    handleFacebook(item) {
      const extras = '&caption=caption&description=DESCRIPTION';
      // const url = 'https://'+window.location.hostname+'/proposal/'+item.slug;
      // https://beta.sixacts.org/plain/vote?pid=17&action=vote
      const url = 'https://'+window.location.hostname+'/plain/vote?pid='+item.id;
        window.open('https://www.facebook.com/sharer/sharer.php?u='+url+extras,
            'facebook-share-dialog',"width=626, height=436"
        );
    }

    createMarkup(txt) {
        return {__html: txt};
    }

    handleTwitter(item) {
        let text = 'Six Acts to reboot democracy\n\nNew act proposed\n';
        text += item.title;
        text += '\n';
        text += 'https://'+window.location.hostname+'/proposal/'+item.slug;
        text = encodeURI(text);
        const popup = window.open('https://twitter.com/intent/tweet?text='+text,
            'popupwindow',
            'scrollbars=yes,width=800,height=400'
        );
    }

    handlePrintArticle(item) {
        const url = 'https://'+window.location.hostname+'/proposal/'+item.slug+'?target=printer';
        window.open(url, 'print-dialog');
    }

    handleVote(item, ctx) {
        // this.setState({item:item})
        // console.log('handleVote', ctx, item.id, window.Laravel);
        const bearer = 'Bearer ' + window.token;
        fetch('/api/vote/', {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'Authorization': bearer,
            },
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
        if (results[0] === 'unauthorized') {
            this.notify('You need to be logged in to vote!', 'error', 3000);
            console.log('401 unauthorized');
            return;
        }
        this.getProposals();
        this.notify(results.message, results.type, 3000);
    }

    componentDidMount() {
        this.getProposals();
        if (window.keepalive) {
            this.setupSocket();
        }
/*
        let el = document.getElementById('proposals');
        el.style.opacity = '1.00';
*/
    }

    componentWillUnmount() {
        this.echo.disconnect();
    }
    // not in use for categories
    handleClickOutside = event => {
        console.log(event);
    }

    setupEcho() {
        this.echo = new Echo({
            broadcaster: 'socket.io',
            client: Socketio,
            host: 'https://' + window.location.hostname + ':6001/'
        });

        console.log('Joined channel: "messages"');
        this.echo.channel('6_acts_database_messages')
            .listen('.NewMessage', (e) => {
                console.log('Message received');
                if (e.hasOwnProperty("politburo")) {
                    this.notify(e.message, e.type, 3000);
                }
                console.log(e);
                if (e.message == 'refresh') {
                    this.getProposals();
                } else {
                    console.log("Other message: " + e.message)
                }
            });
    }

    setUpNchan() {
        let url = window.location.protocol + '//' + window.location.hostname + '/sub?id=messages';
        let lastEventId = window.localStorage.getItem('lastEventId');
        if (lastEventId !== null) {
            url = url + '&last_event_id='+lastEventId;
        }

        let opt = {
            subscriber: ['websocket', 'eventsource'],
            reconnect: 'persist',
            shared: false
        };

        let sub = new NchanSubscriber(url, opt);
        console.log('Joined channel: "messages"');
        if (window.d === true) {
            console.log(sub);
        }
        sub.on("message", (message, message_metadata) =>  {
            // message is a string
            // message_metadata is a hash that may contain 'id' and 'content-type'
            if (window.d===true) {
                console.log(message_metadata);
            }
            window.localStorage.setItem('lastEventId', message_metadata.id);
            let msg = JSON.parse(message);
            if (window.d === true) {
                console.log(msg);
            }
            if (msg.hasOwnProperty("politburo")) {
                this.notify(msg.message, msg.type, 3000);
            }
            if (msg.message === 'refresh') {
                if (window.d===true) {
                    console.log('Refreshing...');
                }
                this.getProposals();
            } else {
                console.log("Other msg: " + msg.message)
            }

        });

        sub.on('connect', function(evt) {
            //fired when first connected.
            if (window.d === true) {
                console.log('connected');
                console.log(evt);
            }
        });

        sub.start();
    }

    setupSocket() {
        if (window.sock === 'nchan') {
            console.log('Setting up nchan');
            this.setUpNchan();
        } else {
            console.log('Setting up echo');
            this.setupEcho();
        }
    }

    async getProposals() {
        const cats = this.state.categories.filter(function(cat) {
            return cat.selected === true
        }).map(function(cat) {
           return cat.id;
        });

        let catsQuery = cats.join(':');
        let proto = window.location.protocol + '//';
        let hostName = window.location.hostname;
        let uid = '&user_id='+window.Laravel.user;
        let filter = this.state.filter;
        let q = this.state.query;

        fetch(proto + hostName + '/api/proposals?cats='+catsQuery+uid+'&filter='+filter+'&q='+q,
            {
                crossDomain: true,
            }
        )
            .then(results => results.json())
            .then(results => this.setState( {'items': results})  )
    }

    getCategoriesUpdateFromChild(cats) {
        console.log(cats);
        this.state.categories = cats;
        this.getProposals();
        if (window.d) {
            console.log('Got proposals');
            console.log(this.state.items);
        }
    }

    getFiltersUpdateFromChild(filter) {
        this.state.filter = filter;
        this.getProposals();

        console.log('Have apparently updated my list...');
        console.log(this.state.items);
    }

    juggler(display) {
        if (display === 'expanded') {
            return 'collapsed';
        } else {
            return 'expanded';
        }
    }

    handleChange(event) {
        this.state.query = event.target.value;
        this.getProposals();
        console.log(event.target.value);
    }

    render() {
        return (
            <div>
                <Router>
                    <Portal>
                        <Categories getCategoriesUpdateFromChild={this.getCategoriesUpdateFromChild}/>
                    </Portal>
                    <Portal>
                        <Filters getFiltersUpdateFromChild={this.getFiltersUpdateFromChild}/>
                    </Portal>
                    <form autoComplete="off" method="post" action="">
                        <div className="field is-horizontal ">
                            <div className="field-body u-mbottom-20">
                                <div className="field">
                                    <div className="control">
                                        <input className="input"
                                               type="text"
                                               placeholder="Search"
                                               onChange={this.handleChange}
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <ul>
                        {this.state.items.map((item, index) => {
                                return (
                                    <div key={index} className="u-mtop-2">
                                        <span className="subtitle has-text-weight-bold">
                                            {item.title}
                                        </span>
                                        <span
                                            className={
                                                `tag is-small u-mleft-15 ${item.category_class} ${item.category_sub_class}`
                                            }
                                            >
                                            {item.category_short_title.substr(0, 1)}
                                        </span>
                                        <div className={`expander ${this.juggler(item.display)} '}`}
                                             id={'expander_1_'+index}
                                            >
                                            {letsDisco(item.body, 228)}&nbsp;&nbsp;
                                            <span className="icon">
                                                <a onClick={() =>
                                                    this.handleExpand(index, 'expanded', 'expander_1_'+index)}
                                                >
                                                    <i className="fa fa-plus"> </i>
                                                </a>
                                            </span>
                                        </div>
                                        <div className={`expandable ${item.display}`} id={'expander_2_'+index}
                                        >
                                            <span dangerouslySetInnerHTML={{ __html: item.body }}></span>
                                            <span className="icon">
                                                <a onClick={() =>
                                                    this.handleExpand(index, 'collapsed', 'expander_2_'+index)}
                                                >
                                                    <i className="fa fa-minus"> </i>
                                                </a>
                                            </span>
                                        </div>
                                        <div className="author controls u-mtop-10 u-mright-10 u-mbottom-10">
                                            <i>Posted by</i>:&nbsp;
                                            <b>
                                                {item.user_display_name != '' && item.user_display_name != null
                                                    ? item.user_display_name
                                                    : item.user_name
                                                }</b>
                                            &nbsp;
                                        </div>
                                        <div className="aggs controls u-mbottom-20">
                                            <span className="numVotes">
                                            {item.aggs_total_votes ? item.aggs_total_votes : ' 0'}</span> votes
                                                <span className="icon u-mleft-20">
                                                {!item.hasOwnProperty('myvote') || item.myvote.vote == 0 ?
                                                    (
                                                    <a onClick={() => this.handleVote(item, 'vote')}>
                                                        <i className="fa fa-arrow-alt-circle-up">&nbsp;</i>
                                                    </a>
                                                    ) : (
                                                    <a onClick={() => this.handleVote(item, 'vote')}>
                                                        <i className="fa fa-minus-circle">&nbsp;</i>
                                                    </a>

                                                    )
                                                }
                                            </span>
    {/*
                                            <span className="icon">
                                                <a onClick={() => this.handleVote(item, 'vote')}>
                                                    <i className="fa fa-minus-circle">&nbsp;</i>
                                                </a>
                                            </span>
    */}
                                            <span className="numDislikes">
                                                <span className="icon u-mleft-10 u-mright-5">
                                                    {item.hasOwnProperty('myvote') && item.myvote.dislike >  0 ?
                                                        (
                                                        <a onClick={() => this.handleVote(item, 'dislike')}>
                                                            <i className="fas fa-thumbs-up thumb-olive">&nbsp;</i>
                                                        </a>
                                                        ) : (
                                                            <a onClick={() => this.handleVote(item, 'dislike')}>
                                                                <i className="fas fa-thumbs-down thumb-purple">&nbsp;</i>
                                                            </a>
                                                        )

                                                    }
                                                </span>
                                                {item.aggs_total_dislikes ? item.aggs_total_dislikes : ' 0'}
                                            </span> dislikes

                                            <div className={'icon theworks'}>
                                                <a className="button"
                                                   onClick={() => this.handlePrintArticle(item)}
                                                   rel={'nofollow'}
                                                   target={'_blank'}
                                                >
                                                  <span className="icon is-small">
                                                    <i className="fas fa-print"> </i>
                                                  </span>
                                                </a>&nbsp;
                                                <a className="button"
                                                   data-size="large"
                                                   onClick={() => this.handleTwitter(item)}
                                                >
                                                  <span className="icon is-small">
                                                    <i className="fab fa-twitter"> </i>
                                                  </span>
                                                </a>&nbsp;
                                                <a className="button"
                                                   onClick={() => this.handleFacebook(item)}
                                                   rel={'nofollow'}
                                                   target={'_blank'}
                                                >
                                                  <span className="icon is-small">
                                                    <i className="fab fa-facebook-f"> </i>
                                                  </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                )
                            }
                        )}
                    </ul>
                </Router>
                <ToastContainer/>
            </div>
        );
    }
}

if (document.getElementById('proposals')) {
    ReactDOM.render(<Proposals/>, document.getElementById('proposals'));
}

if (document.getElementById('splash')) {
    const props = {};
    ReactDOM.render(<SplashPortal {...props}/>, document.getElementById('splash'));
}

if (document.getElementById('countdown')) {
    // Renderer callback with condition
    const renderer = ({days, hours, minutes, seconds, completed}) => {
        if (completed) {
            // Render a completed state
            return <Completionist />;
        } else {
            // Render a countdown
            return <span><b>{days}</b> days, {hours}:{minutes}:{seconds}</span>;
        }
    };

    ReactDOM.render(
        <Countdown
            date={new Date('August 17, 2019 16:00:00').getTime()}
            renderer={renderer}
        />,
        document.getElementById('countdown')
    );
}

function letsDisco(theText, maxLength) {
    return _.truncate(theText, {
          length: maxLength,
            separator: /,?\.* +/ // separate by spaces, including preceding commas and periods
        }
    )
};

