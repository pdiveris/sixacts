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
        fetch('https://sixacts.div/api/proposals', {
            crossDomain:true,
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
                        <div key={index} className="u-mtop-10">
                            <h1 className="subtitle">{item.title}</h1>
                            <p>{letsDisco(item.body)}</p>
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

function letsDisco(theText, theSize) {
/*
    let buffy = theText;
*/
    let promptLine =  theText.replace(/^(.{82}[^\s]*).*/, "$1");;
    let buffy =
        promptLine +
        'KANGA' +
        _.replace(theText, promptLine, '');

    return buffy;
};
