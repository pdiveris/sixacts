import React, {Component} from 'react';
import ReactDOM from 'react-dom';

export default class Proposals extends Component {
    constructor() {
        super();
        this.state = {
            'items': []
        }
    }
    componentDidMount() {
        this.getProposals();
    }
    getProposals() {
        fetch('https://sixacts.div/api/proposals')
            .then(results => results.json())
            .then(results => this.setState({'items': results}))
    }
    render() {
        return (
            <ul>
                {this.state.items.map(function (item, index) {
                    return (
                        <div key={index}>
                            <h1 className="subtitle">{item.title}</h1>
                            <p>{item.body}</p>
                        </div>
                    )
                   }
                )}
            </ul>

        );
    }
}

if (document.getElementById('proposals')) {
    ReactDOM.render(<Proposals/>, document.getElementById('proposals'));
}
