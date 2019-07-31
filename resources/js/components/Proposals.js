import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import Echo from "laravel-echo";
import Socketio from "socket.io-client";
import {ToastContainer, toast} from 'react-toastify';
import Modal from 'react-modal';
import Countdown from 'react-countdown-now';
import 'react-toastify/dist/ReactToastify.css';
// minified version is also included
// import 'react-toastify/dist/ReactToastify.min.css';

const portalRoot = document.getElementById("cats");
const splashPortalRoot = document.getElementById("splash");

const customStyles = {
    overlay: {
        position: 'fixed',
        top: 0,
        left: 0,
        right: 0,
        bottom: 0,
        backgroundColor: 'rgba(255, 255, 255, 0.75)'
    },
    content : {
        top                   : '50%',
        left                  : '50%',
        right                 : 'auto',
        bottom                : 'auto',
        marginRight           : '-30%',
        transform             : 'translate(-50%, -50%)'
    }
};

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

class SplashPortal extends React.Component {
    constructor (props) {
        super(props);
        this.state = {
            showModal: window.showSplash
        };

        this.handleOpenModal = this.handleOpenModal.bind(this);
        this.handleCloseModal = this.handleCloseModal.bind(this);
    }

    handleOpenModal () {
        this.setState({ showModal: true });
    }

    handleCloseModal () {
        this.setState({ showModal: false });
    }

    render () {
        return (
            <div>
                <Modal
                    isOpen={this.state.showModal}
                    contentLabel="Minimal Modal Example"
                    style={customStyles}
                    aria={{
                        labelledby: "heading",
                        describedby: "full_description"
                    }}>
                    <div className="box content">
                        <h1>Six Acts</h1>
                        <img src={'/images/6_acts_logo.png'} width={'200'}/>
                        <h2 className="title is-6">
                            <i>
                                The Six Acts project is an online platform to crowd-source radical ideas for
                                improving and redesigning the way we practice democracy in the 21st century. </i>
                        </h2>
                        <p>
                            The poll will open for submissions and voting at 4pm on the 17th August 2019.
                        </p>
                        <p>
                            We look forward to your participation. In the meantime, please familiarise yourself with the
                            information on this page which explains how the poll will be conducted and help you ensure
                            that
                            your proposals are submitted correctly.
                        </p>
                        <p>
                            Get thinking, get your acts together, and we'll see you back here on the 17th August!

                        </p>
                        <p>
                            The clock is ticking! <span id={"countdown"}></span>
                        </p>
                    </div>
                </Modal>
            </div>
        );
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
        const {id} = event.target;
        let pos = id.substring(id.indexOf('_')+1);
        this.state.categories[pos-1].selected = !this.state.categories[pos-1].selected ;
        this.forceUpdate();
        this.getProposals();
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

    handleExpand(index, display, elId) {
        const { items } = this.state;
        items[index].display = display;
        // update state
        this.setState({
            items,
        });
        console.log('Handling expand: '+display);
    }

    handleFacebook(item) {
      const url = 'https://'+window.location.hostname+'/proposal/'+item.id;
        window.open('https://www.facebook.com/sharer/sharer.php?u='+url,
            'facebook-share-dialog',"width=626, height=436"
        );
    }

    handleTwitter(item) {
        let text = 'Six Acts to reboot democracy\n\nNew act proposed\n';
        text += item.title;
        text += '\n';
        text += 'https://'+window.location.hostname+'/proposal/'+item.id;
        text = encodeURI(text);
        const popup = window.open('https://twitter.com/intent/tweet?text='+text,
            'popupwindow',
            'scrollbars=yes,width=800,height=400'
        );
        console.log('twatting...');
    }

    handlePrintArticle(item) {
        console.log(item);
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
        this.notify(results.message, results.type, 3000);
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
    // not in use for categories
    handleClickOutside = event => {
        console.log(event);
    }

    setupSocket() {
        this.echo = new Echo({
            broadcaster: 'socket.io',
            client: Socketio,
            host: 'https://' + window.location.hostname + ':'+window.echoPort+'/'
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
                    console.log("Unexpected message: " + e.message)
                }
            });
    }

    getProposals() {
        const cats = this.state.categories.filter(function(cat) {
            return cat.selected === true
        }).map(function(cat) {
           return cat.id;
        });
        let catsQuery = cats.join(':');
        let proto = window.location.protocol + '//';
        let hostName = window.location.hostname;
        fetch(proto + hostName + '/api/proposals?cats='+catsQuery, {
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

    faded(status) {
        // return (isMember ? "$2.00" : "$10.00");
        if (status) {
            return 'full';
        } else {
            return 'pale';
        }
    }
    juggler(display) {
        if (display === 'expanded') {
            return 'collapsed';
        } else {
            return 'expanded';
        }
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
                                            <span
                                                className={`xbutton`}
                                            >
                                                <a id={`Katze_${cat.id}`} href={"#"}
                                                   onClick={this.onClick}
                                                   className={`button ${cat.class} 
                                                    ${this.faded(cat.selected)}`
                                                   }
                                                >
                                                {cat.short_title}
                                                </a>
                                        </span>
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
                                            `tag is-small u-mleft-15 ${item.category.class} ${item.category.sub_class}`
                                        }
                                        >
                                        {item.category.short_title.substr(0, 1)}
                                    </span>
                                    <div className={`expander ${this.juggler(item.display)} '}`}
                                         id={'expander_1_'+index}
                                        >
                                        {letsDisco(item.body, 200)}&nbsp;&nbsp;
                                        <span className="icon">
                                            <a href={'#'} onClick={() =>
                                                this.handleExpand(index, 'expanded', 'expander_1_'+index)}
                                            >
                                                <i className="fa fa-plus"> </i>
                                            </a>
                                        </span>
                                    </div>
                                    <div className={`expandable ${item.display}`} id={'expander_2_'+index}>
                                        {item.body}
                                        <span className="icon">
                                            <a href={'#'} onClick={() =>
                                                this.handleExpand(index, 'collapsed', 'expander_2_'+index)}
                                            >
                                                <i className="fa fa-minus"> </i>
                                            </a>
                                        </span>
                                    </div>
                                    <div className="author controls u-mtop-10 u-mright-10 u-mbottom-10">
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
            return <Completionist/>;
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

function letsTango(theText, theSize) {
    let promptLine = theText.replace(/^(.{82}[^\s]*).*/, "$1");
    return _.replace(theText, promptLine, '');
};

function share_fb(url) {
    window.open('https://www.facebook.com/sharer/sharer.php?u='+url,
        'facebook-share-dialog',"width=626, height=436"
    )
}
