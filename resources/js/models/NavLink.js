export default class NavLink {

    constructor(name, icon, route, visible) {
        this.name = name;
        this.icon = icon;
        this.route = route;
        this.visible = visible;
    }

    isActive() {
        const route = window.location.href;
        return route.indexOf(this.route) !== -1;
    }

    getIcon() {
        return `${this.icon}`
    }

}
