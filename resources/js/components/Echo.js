import React, {Component} from 'react';
import ReactDOM from 'react-dom';

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

        // let nchan = window.location.scheme + '://' + window.location.hostname + '/sub?id=messages';
        // let nchan = 'https://sixacts.div/sub?id=messages';
        // &last_event_id=1565224710:0
        let nchan = window.location.protocol + '//' + window.location.hostname + '/sub?id=messages';
        let lastEventId = window.localStorage.getItem('lastEventId');
        if (lastEventId !== null) {
            nchan = nchan + '&last_event_id='+lastEventId;
        }
        console.log('URL set to '+ nchan);

        function onNewUpdate(message){
            console.log(message);
            window.localStorage.setItem('lastEventId', message.lastEventId);
            console.log('Message received');
        }
        const source = new EventSource(nchan);
        source.onmessage = onNewUpdate;
    }

    render() {
        return (
            <div>
                <h1 className="title is-3">Echo</h1>
            </div>
        );
    }
}

if (document.getElementById('echo')) {
    ReactDOM.render(<Echo/>, document.getElementById('echo'));
}

