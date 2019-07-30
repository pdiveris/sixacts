import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import Socketio from "socket.io-client";
// import Echo from 'laravel-echo';
// import Socketio from 'socket.io-client';

export default class Echo extends Component {
    constructor(props) {
        super(props);
        this.state = {
            'country': 'Kongo'
        }
        this.echo = null;
        this.onClick = this.handleClick.bind(this);

    }
    handleClick(event) {
        const {id} = event.target;
    }

    componentDidMount() {
        if (window.keepalive) {
            this.setupSocket();
        }
    }

    componentWillUnmount() {

    }

    setupSocket() {
        console.log('Setting up socket.IO');
        console.log('Hostname set to '+ window.location.hostname + ':'+window.echoPort);
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
                    console.log("Unexpected message: " + e.message)
                }
            });
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
            </div>
        );
    }
}

if (document.getElementById('echo')) {
    ReactDOM.render(<Echo/>, document.getElementById('echo'));
}

