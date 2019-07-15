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
                            <span className="subtitle has-text-weight-bold">{item.title}</span>
                            <span
                                className={
                                    `tag is-small u-mleft-15
                                    ${item.category.class}
                                    ${item.category.sub_class}`
                                }
                            >
                            {item.category.short_title.substr(0, 1)}
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
                            <div className="author controls u-mbottom-20 u-mtop-10 u-mright-10">
                                {item.user.display_name != ''
                                    ? item.user.display_name
                                    : item.user.name
                                }
                                &nbsp;
                            </div>
                            <div className="aggs controls u-mbottom-20 u-mtop-10">
                                {item.aggs.length > 0 ? item.aggs[0].total_votes : '0 '} VOTES
                                <span className="icon u-mleft-20">
                                    <i className="fa fa-arrow-alt-circle-up"></i>
                                </span>
                                <span className="icon">
                                    <i className="fa fa-arrow-alt-circle-down"></i>
                                </span>
                            </div></div>
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
    let ret = theText.replace(/^(.{222}[^\s]*).*/, "$1");
    return ret;
};

function letsTango(theText, theSize) {
    let promptLine = theText.replace(/^(.{82}[^\s]*).*/, "$1");
    return _.replace(theText, promptLine, '');
};

