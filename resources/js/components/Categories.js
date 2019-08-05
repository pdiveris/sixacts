import React, {Component} from 'react';
import ReactDOM from 'react-dom';

export default class Categories extends Component {
    constructor(props) {
        super(props);
        this.state = { 'categories': [] };
        this.onClick = this.handleClick.bind(this);
    }

    handleClick(event) {
        const {id} = event.target;
        let pos = id.substring(id.indexOf('_')+1);
        this.state.categories[pos-1].selected = !this.state.categories[pos-1].selected ;
        this.forceUpdate();
        this.props.getCategoriesUpdateFromChild(this.state.categories);
    }

    componentDidMount() {
        this.getCategories();
    }

    componentWillUnmount() {
    }

    getCategories() {
        let proto = window.location.protocol + '//';
        let hostName = window.location.hostname;
        fetch(proto + hostName + '/api/categories?addSelected=1', {
                crossDomain: true,
            }
        )
            .then(results => results.json())
            .then(results => this.setState({'categories': results}))
    }

    faded(status) {
        if (status) {
            return 'full';
        } else {
            return 'pale';
        }
    }

    juggler(display) {
        if (display === 'expanded') {
            return 'collapsed';
        } else {
            return 'expanded';
        }
    }

    render() {
        return (
            <div>
                <ul className={"categoriesList"}>
                    {this.state.categories.map((cat, index) => {
                            return (
                                <li key={`cat_${cat.id}`}>
                                            <span
                                                className={`xbutton`}
                                            >
                                                <a id={`Katze_${cat.id}`}
                                                   onClick={this.onClick}
                                                   className={`button ${cat.class} 
                                                    ${this.faded(cat.selected)}`
                                                   }
                                                >
                                                {cat.short_title}
                                                </a>
                                        </span>
                                </li>
                            )
                        }
                    )}
                </ul>
            </div>
        );
    }
}

