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
        console.log('Filters loaded');
    }

    componentWillUnmount() {

    }

    getClass(el) {
        if (this.state.filter === el) {
            return 'button is-small is-dark';
        }
        return 'button is-small text-purple';
    }

    render() {
        return (
            <div className={'u-mbottom-20'}>
                <p className="menu-label">
                    View By
                </p>
                <p>
                    <span className="is-small u-m-5">
                        <a className={this.getClass('most')} id="most" onClick={this.onClick}>
                            Most votes
                        </a>
                    </span>
                    <span className="is-small u-m-5">
                        <a className={this.getClass('recent')} id="recent" onClick={this.onClick}>
                            Most recent
                        </a>
                    </span>
                </p>
{/*
                <p className="u-mtop-10">
                    <span className="is-small u-m-5">
                        <a className={this.getClass('current')} id="current" onClick={this.onClick}>
                            Current document
                        </a>
                    </span>
                </p>
*/}
                <p className="u-mtop-10">
                    <span className="is-small u-m-5">
                        <a className={this.getClass('dislikes')} id="dislikes" onClick={this.onClick}>
                            Most disliked
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

