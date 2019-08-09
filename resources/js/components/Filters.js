import React, {Component} from 'react';
import ReactDOM from 'react-dom';

export default class Filters extends Component {
    constructor(props) {
        super(props);
        this.state = {
            'filter': ''
        }
        this.echo = null;
        this.onClick = this.handleClick.bind(this);

    }
    handleClick(event) {
        const {id} = event.target;
        this.state.filter = id;
        this.forceUpdate();
        this.props.getFiltersUpdateFromChild(this.state.filter);
    }

    componentDidMount() {
        console.log('Mount we did (filters)');
    }

    componentWillUnmount() {

    }

    render() {
        return (
            <div className={'u-mbottom-20'}>
                <p className="menu-label">
                    View By
                </p>
                <p>
                    <span className="button is-small">
                        <a className="text-purple" id="most" onClick={this.onClick}>
                            Most votes
                        </a>
                    </span>
                    <span className="button is-small">
                        <a className="text-purple" id="recent" onClick={this.onClick}>
                            Most recent
                        </a>
                    </span>
                </p>
                <p className="u-mtop-10">
                    <span className="button is-small">
                        <a className="text-purple" id="current" onClick={this.onClick}>
                            Current document
                        </a>
                    </span>
                </p>
            </div>
        );
    }
}
/*

if (document.getElementById('filters')) {
    ReactDOM.render(<Filters/>, document.getElementById('filters'));
}
*/

