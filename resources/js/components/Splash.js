import React, {Component} from 'react';
import Modal from "react-modal";

const customStyles = {
    overlay: {
        position: 'fixed',
        top: 0,
        left: 0,
        right: 0,
        bottom: 0,
        backgroundColor: 'rgba(255, 255, 255, 0.75)'
    },
    content : {
        top                   : '50%',
        left                  : '50%',
        right                 : 'auto',
        bottom                : 'auto',
        marginRight           : '-30%',
        transform             : 'translate(-50%, -50%)'
    }
};

export default class SplashPortal extends React.Component {
    constructor (props) {
        super(props);
        this.state = {
            showModal: window.showSplash
        };

        this.handleOpenModal = this.handleOpenModal.bind(this);
        this.handleCloseModal = this.handleCloseModal.bind(this);
    }

    handleOpenModal () {
        this.setState({ showModal: true });
    }

    handleCloseModal () {
        this.setState({ showModal: false });
    }

    render () {
        return (
            <div>
                <Modal
                    isOpen={this.state.showModal}
                    contentLabel="Minimal Modal Example"
                    style={customStyles}
                    aria={{
                        labelledby: "heading",
                        describedby: "full_description"
                    }}>
                    <div className="box content" id={'modal'}>
                        <h1>Six Acts</h1>
                        <img src={'/images/6_acts_logo.png'} width={'200'}/>
                        <h2 className="title is-6">
                            <i>
                                The Six Acts proposals and voting site where you'll be able to input into the idea pool
                                for radical democratic action.
                            </i>
                        </h2>
                        <p>
                            The poll will open for submissions and voting at 4pm on the 17th August 2019.
                        </p>
                        <p>
                            We look forward to your participation. In the meantime, please familiarise yourself with the
                            information on this page which explains how the poll will be conducted and help you ensure
                            that
                            your proposals are submitted correctly.
                        </p>
                        <p>
                            Get thinking, get your acts together, and we'll see you back here on the 17th August!

                        </p>
                        <p>
                            The clock is ticking! <span id={"countdown"}></span>
                        </p>
                    </div>
                </Modal>
            </div>
        );
    }
}
