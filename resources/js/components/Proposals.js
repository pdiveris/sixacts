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
                        <div key={index} className="u-mtop-2">
                            <span className="subtitle">{item.title}</span>
                            <span class="tag is-primary is-small  u-mleft-20">
                                {item.category.class}
                            </span>
                            <div className="expander">
                                {letsDisco(item.body)}&nbsp;&nbsp;
                                <span className="icon">
                                    <i className="fa fa-plus"></i>
                                </span>
                            </div>
                            <div className="expandable collapsed">
                                {letsTango(item.body)}
                            </div>
                            <div className="aggs controls u-mbottom-20">
                                {item.aggs.length > 0 ? item.aggs[0].total_votes : 'NO '} VOTES
                            </div>
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
    let ret =  theText.replace(/^(.{82}[^\s]*).*/, "$1");
    return ret;
};

function letsTango(theText, theSize) {
    let promptLine =  theText.replace(/^(.{82}[^\s]*).*/, "$1");
    return _.replace(theText, promptLine, '');
};

