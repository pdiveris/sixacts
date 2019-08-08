import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import Echo from 'laravel-echo';
import Socketio from 'socket.io-client';

export default class Tablets extends Component {
    constructor(props) {
        super(props);
        this.state = {
            'items': [],
            'country': 'Kongo'
        }
        this.echo = null;
        this.onClick = this.handleClick.bind(this);
        this.refresh = this.handleRefresh.bind(this);
        this.stream = this.setupStream.bind(this);
    }

    handleClick(event) {
        const {id} = event.target;
        this.getProposals();
    }

    handleRefresh(event) {
        const {id} = event.target;
        this.getProposals();
    }

    setupSocket() {
        console.log('Setting up socket.io');
        console.log('Hostname set to '+ window.location.hostname);
        this.echo = new Echo({
            broadcaster: 'socket.io',
            client: Socketio,
            host: 'https://' + window.location.hostname + ':6001/'
        });

        console.log('About to set to listening to "messages" for "NewMessage"');
        this.echo.channel('6_acts_database_messages')
            .listen('.NewMessage', (e) => {
                console.log('Message received');
                console.log(e);
                if (e.message == 'refresh') {
                    this.getProposals();
                } else {
                    console.log("Unexpected message: "+e.message)
                }
            });
    }

    setupStream() {
        let es = new EventSource('https://sixacts.div/sse/semaphore');
        es.addEventListener('message', event => {
            if (event.data.length > 0) {
                console.log('Got broadcast');
                console.log(event.data);
                this.setState({'country': 'Senegal'});
                this.setState({'items': JSON.parse(event.data)});
            }
        }, false);

    }

    componentDidMount() {
        this.getProposals();
        this.setupSocket();
        // this.setupStream();

    }

    componentWillUnmount() {
        this.echo.disconnect();
    }

    getProposals() {
        console.log("getProposals()");

        fetch('https://sixacts.div/api/names/random', {
                crossDomain: true,
            }
        )
            .then(results => results.json())
            .then(results => this.setState({'items': results}));

    }

    render() {
        return (
            <div>
                <div className="u-mb-20">
                    <a id="butt_1" onClick={this.onClick}
                       className="button is-medium is-black">Fetch!
                    </a>
                    <a id="butt_2" onClick={this.refresh}
                       className="u-mleft-20  button is-medium is-info">CLICK
                    </a>
                </div>
                <ul>
                    {this.state.items.map(function (item, index) {
                            return (
                                <div key={index} className="u-mtop-2">
                                    <span className="subtitle">{item.name}</span>
                                </div>
                            )
                        }
                    )}
                </ul>
            </div>
        );
    }
}

if (document.getElementById('tablets')) {
    ReactDOM.render(<Tablets/>, document.getElementById('tablets'));
}

