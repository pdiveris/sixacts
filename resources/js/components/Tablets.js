import React, {Component} from 'react';
import ReactDOM from 'react-dom';

export default class Tablets extends Component {
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
        console.log("getProposals()");
        let results = [
                {'title': 'Pako was here'},
                {'title': 'So was Mary'},
                {'title': 'Tash was away, he was having a nap'},
                {'title': 'Perdo was at the abattoir, programming'},
        ];
        this.setState({'items': results})
/*

        fetch('https://sixacts.div/api/proposals', {
                crossDomain: true,
            }
        )
            .then(results => results.json())
            .then(results => this.setState({'items': results}))
*/
    }

    render() {
        return (
            <ul>
                {this.state.items.map(function (item, index) {
                    return (
                        <div key={index} className="u-mtop-2">
                            <span className="subtitle has-text-weight-bold">{item.title}</span>
                        </div>
                    )
                }
                )}
            </ul>
        );
    }
}

if (document.getElementById('tablets')) {
    ReactDOM.render(<Tablets/>, document.getElementById('tablets'));
}

function letsDisco(theText, theSize) {
    let ret = theText.replace(/^(.{222}[^\s]*).*/, "$1");
    return ret;
};

function letsTango(theText, theSize) {
    let promptLine = theText.replace(/^(.{82}[^\s]*).*/, "$1");
    return _.replace(theText, promptLine, '');
};

