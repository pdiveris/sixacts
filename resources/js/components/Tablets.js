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
/*        let results = [
                {'title': 'Gai-Jin: The Epic Novel of the Birth of Modern Japan'},
                {'title': 'Welcome to Lagos'},
                {'title': 'Berlin Alexanderplatz'},
                {'title': 'The Book Smugglers of Timbuktu'},
                {'title': 'District 13: The Drama of the Armed Conflict in Medellin, Colombia'},
                {'title': 'Paradise Lost: The Destruction of Islams City of Tolerance: Smyrna 1922'},
                {'title': 'Dear Los Angeles: The City in Diaries and Letters, 1542 to 2018'},
                {'title': 'Trieste and the Meaning of Nowhere'},
                {'title': 'The Alhambra and the Kremlin : travels in the South and the North of Europe'},
                {'title': 'Algiers, Third World Capital: Freedom Fighters, Revolutionaries, Black Panthers'}
        ];
        this.setState({'items': results})*/

        fetch('https://sixacts.div/api/names', {
                crossDomain: true,
            }
        )
            .then(results => results.json())
            .then(results => this.setState({'items': results}))

    }

    render() {
        return (
            <ul>
                {this.state.items.map(function (item, index) {
                    return (
                        <div key={index} className="u-mtop-2">
                            <span className="subtitle has-text-weight-bold">{item.name}</span>
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

