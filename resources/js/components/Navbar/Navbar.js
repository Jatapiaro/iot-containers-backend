import React, { Component } from 'react';
import TopHeader from './TopHeader';
import Menu from './Menu';
import NavLink from './../../models/NavLink';

export default class Navbar extends Component {

    state = {
        isCollapsedMenuOpen: false
    }

    constructor(props) {
        super(props);
        this.navlinks = [
            new NavLink(
                'Mi Perfil',
                'fa fa-user',
                '/me',
                true),
        ];
    }

    toggleCollapsedMenu = () => {
        this.setState((prevState) => {
            return {
                isCollapsedMenuOpen: !prevState.isCollapsedMenuOpen
            }
        })
    }

    render() {

        return (
            <React.Fragment>
                <TopHeader
                    toggleCollapsedMenu={this.toggleCollapsedMenu}/>
                <Menu
                    navlinks={this.navlinks}
                    isCollapsedMenuOpen={this.state.isCollapsedMenuOpen}/>
            </React.Fragment>
        );

    }

}
