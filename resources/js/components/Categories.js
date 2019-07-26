import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
// import Echo from 'laravel-echo';
// import Socketio from 'socket.io-client';

export default class Categories extends Component {
    constructor(props) {
        super(props);
        this.state = {
            'country': 'Kongo'
        }

    }

    componentDidMount() {
        // this.getProposals();
    }

    componentWillUnmount() {
        // this.echo.disconnect();
    }

    getCategories() {
        console.log("getCategories()");
        fetch('https://sixacts.div/api/categories/', {
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

if (document.getElementById('cats')) {
    ReactDOM.render(<Categories/>, document.getElementById('cats'));
}

