class Sway {

    components = Array();
    element;
    static instance = new Sway();

    mount(element) {
        this.element = document.getElementById(element);
        console.log("[Sway] App successfully mounted!")
    }

    addComponent(component) {
        component.id = this.generateId();
        component.build(this);

        this.components[component.id] = component;
    }

    addHtmlElement(element){
        let newElement = document.createElement("div");
        newElement.innerHTML = element.trim();

        element = newElement.firstChild;

        this.element.appendChild(element);
    }

    generateId(){
        return "10000000-1000-4000-8000-100000000000".replace(/[018]/g, c =>
            (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
        );
    }

}