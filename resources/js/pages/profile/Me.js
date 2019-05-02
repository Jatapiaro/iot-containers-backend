import React, {Component} from 'react';

export default class Me extends Component {

    constructor(props) {
        super(props);
    }

    /**
     * Before the component is rendered
     */
    componentWillMount() {
        this.props.userService.me()
            .then(res => {
                console.log(res);
            })
            .catch(err => {
                console.log(err);
            });
    }

    render() {
        return (
            <div className="container">
                Esto es aún una prueba
            </div>
        );
    }

}
