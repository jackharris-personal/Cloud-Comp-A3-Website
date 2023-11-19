class UIComponent {

    element;
    properties;
    id;
    parent;
    container;
    children = Array();
    buildCallback;
    requiresContainer;

    constructor(properties) {
        this.properties = properties;
        this.requiresContainer = false;
    }

    build(parent){
        let type = this.constructor.name;

        let newElement = document.createElement(type);
        let newElementContainer = document.createElement("div");
        this.container = newElementContainer;

        newElement.id = this.id;

        if(this.buildCallback !== undefined) {
            newElement = this.buildCallback(newElement,this.properties);
        }

        if(this.requiresContainer){
            newElementContainer.appendChild(newElement)
            parent.element.appendChild(newElementContainer);
        }else{
            parent.element.appendChild(newElement);
        }

        this.parent = parent;

        this.element = newElement;
    }


    addComponent(component) {
        component.id = Sway.instance.generateId();
        component.build(this);

        this.children[component.id] = component;
    }

    addHtmlElement(element){
        let newElement = document.createElement("div");
        newElement.innerHTML = element.trim();

        element = newElement.firstChild;

        this.element.appendChild(element);
    }

}

class Form extends UIComponent{

    inputs = Array();

    constructor(properties) {
        super(properties);

    }

    addInput(input){
        this.inputs[input.label] = input;
    }

}

class Input extends UIComponent{

    validation;
    alert;
    label;
    isValid;
    errorMessage;

    constructor(properties) {
        super(properties);

        this.requiresContainer = true;

        this.buildCallback = function (newElement,properties) {
            this.errorMessage = properties.errorMessage;

            this.validation = properties.validation;
            newElement.addEventListener("keyup", e => this.keypressCallback(e));

            if(this.properties.inputType !== undefined){
                newElement.type = properties.inputType;
            }

            newElement.name = this.properties.label;

            this.isValid = false;

            this.generateLabel();

            return newElement;
        }
    }


    keypressCallback(event){
        console.log(event)
        if(this.validation !== undefined) {
            this.toggleAlert(this.validation(this.element.value));
        }
    }
    generateLabel(){
        let label = document.createElement("label");
        label.setAttribute("for",this.id)
        label.innerText = this.properties.label;
        label.style.fontWeight = "bold"
        this.container.insertBefore(label, this.element);
        return label;
    }

    toggleAlert(status){

        if(this.alert === undefined){
            this.alert = document.createElement("p");
            this.alert.innerText = this.properties.errorMessage;
            this.alert.classList.add("input-alert");
            this.container.appendChild(this.alert)
        }

        if(this.element.value.trim().length === 0){
            this.isValid = false;
            this.alert.style.display = "none";
            return;
        }

        if(!status){
            this.isValid = false;
            this.alert.style.display = "block";
        }else{
            this.alert.style.display = "none";
            this.isValid = true;
        }

    }
}

class SmartDropDown extends Input{

    searchInput;
    editInput;

    constructor(properties) {
        super(properties);

        this.searchInput = properties.searchInput;
        this.editInput = properties.editInput;
    }

    renderSearchInput(){

    }


    renderEditInput(){

    }



}

class Button extends UIComponent{

    action;

    constructor(properties) {
        super(properties);
        this.onsubmit = properties.action;
        
        this.buildCallback = function (newElement, properties) {
            newElement.innerText = properties.label;
            newElement.onclick = properties.action;

            return newElement;
        }
    }

    onsubmit(){
        this.action();
    }

}