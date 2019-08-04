import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';

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
        console.log('Mount we did');
        if (window.keepalive) {
            this.setupSocket();
        }
    }

    componentWillUnmount() {

    }

    setupSocket() {
        console.log('Setting up SSE event listener');

        // let nchan = window.location.hostname + '/sub?id=asty';
        let nchan = 'https://beta.sixacts.org/sub?id=asty';
        console.log('URL set to '+ nchan);

        function onNewUpdate(message){
            console.log(message.data);
        }

        const source = new EventSource(nchan);
        source.onmessage = onNewUpdate;

/*
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
*/
    }

    render() {
        return (
            <div>
                PARACHUNA
            </div>
        );
    }
}

if (document.getElementById('echo')) {
    ReactDOM.render(<Echo/>, document.getElementById('echo'));
}

