import React, { Component } from 'react';

export default class TopHeader extends Component {

    logout = () => {
        window.axios.post('/logout')
            .then(res => {
                window.location.href = "/login";
            })
            .catch(error => {
                window.location.href="/login";
            });
    }

    render() {
        return (
            <div>
                <div className="header py-4">
                    <div className="container">
                        <div className="d-flex">
                            <a className="header-brand" href="/">
                                {window.appName}
                            </a>
                            <div className="d-flex order-lg-2 ml-auto">
                                <div className="dropdown">
                                    <a href="#" className="nav-link pr-0 leading-none" data-toggle="dropdown">
                                        <span className="profile">
                                            <div>
                                                <i className="fa fa-user"/><span className="text-default">{` ${window.user}`}</span>
                                            </div>
                                        </span>
                                    </a>
                                    <div className="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a onClick={this.logout} className="dropdown-item">
                                            <i className="dropdown-icon fe fe-log-out"></i> Sign out
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <a onClick={this.props.toggleCollapsedMenu} className="header-toggler d-lg-none ml-3 ml-lg-0" data-toggle="collapse" data-target="#headerMenuCollapse">
                                <span className="header-toggler-icon"></span>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        );
    }

}
