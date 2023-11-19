class Component{

    static types = {
        "String" : {
            name: "String",
            color: "#06be01",
            description: "A sequence of letter and number characters, this can be a letter, word, sentence or paragraph.",
            validate: function (input) {
                return true;
            }
        },
        "Number" : {
            name: "Number",
            color: "#ff8000",
            description: "A numerical number, this can include decimal points.",
            validate: function (input) {
                let regex = new RegExp("^\\d+$");
                return regex.test(input);
            }
        },
        "Color" : {
            name: "Color",
            color: "#00cb7f",
            description: "Color selection, either RGBA() or Color.color",
            validate: function (input) {

                return input.startsWith("Color.");
            }
        }
    }

    static properties = {
        "Text" : {
            name: "Text",
            handler: "component",
            call: "innerText",
            type: "general",
            allowedTypes: [Component.types.Number, Component.types.String],
        },
        "Color": {
            name: "Color",
            handler: "component.css",
            call: "color",
            type: "style",
            allowedTypes: [Component.types.Color]
        },
        "Alignment": {
            name: "Alignment",
            handler: "component.css",
            call: "text-align",
            type: "style",
            allowedTypes: [Component.types.String]
        },
        "Image": {
            name: "Image",
            handler: "component",
            call: "src",
            type: "general",
            allowedTypes: [Component.types.String]
        },
        "Width": {
            name: "Width",
            handler: "component.css",
            call: "width",
            type: "style",
            allowedTypes: [Component.types.String]
        },
        "Height": {
            name: "Height",
            handler: "component.css",
            call: "height",
            type: "style",
            allowedTypes: [Component.types.String]
        },
        "Margin":{
            name: "Margin",
            handler: "component.css",
            call: "margin",
            type: "style"
        },
        "Padding":{
            name: "Padding",
            handler: "component.css",
            call: "padding",
            type: "style"
        },
        "BackgroundColor":{
            name: "Background Color",
            handler: "component.css",
            call: "background-color",
            type: "style"
        },
        "Radius": {
            name: "Radius",
            handler: "component.css",
            call: "border-radius",
            type: "style"
        }

    }

    static propertyCategories = {
        "style": "🎨 Style /",
        "general": "✨",
    }

    component;

    properties = Array();
    allowedTypes = Array();

    selectedProperty;
    id;


    constructor(component) {

        console.log(component)

        this.component = component;
        this.id =  component.id;

        this._addProperty(Component.properties.Margin);

        this._addProperty(Component.properties.Padding);

        this._addProperty(Component.properties.BackgroundColor);

        this._addProperty(Component.properties.Radius);

        this.selectedProperty = this.properties[0];

        console.log("[Component] Created instance with id="+component.id);
    }

    _addProperty(property){
        this.properties.push(property);
    }

    _addAllowedType(type){
        this.allowedTypes.push(type);
    }

    _updatePropertyValue(property,value){
        switch (property.handler) {

            case "component": {
                this.component[property.call] = value;
                console.log("TEST....")
                console.log(this.component[property.call])
                break;
            }
            case "component.css": {
                this.component.style.setProperty(property.call, value);
                break;
            }
            default: {
            }

        }
    }

    getProperty(property){

        let result = "";


        let vars = JSON.parse(this.component.getAttribute("variables"));

        console.log(vars)

        if(vars !== null){

            vars.forEach((item)=>{

                if(property.name === item.property.name){
                    result = item.key;
                }

            })

        }

        if(result === "") {

            switch (property.handler) {

                case "component": {

                    result = this.component[property.call];
                    break;
                }
                case "component.css": {

                    result = window.getComputedStyle(this.component, property.call).getPropertyValue(property.call);
                    break;
                }

                default: {
                    result = "NOT IMPLEMENTED";
                }
            }
        }

        return result;
    }

    updateVariablesCallback(){

        let vars = JSON.parse(this.component.getAttribute("variables"));

        if(vars !== null){
            vars.forEach((item)=>{
                console.log("Updating item: "+item.key+" to "+VariableManager.instance.getVariable(item.key))
                this._updatePropertyValue(item.property,VariableManager.instance.getVariable(item.key));
            })
        }
    }

    setProperty(value){
        let vars = JSON.parse(this.component.getAttribute("variables"));
        this.component.removeAttribute("variables")

        if(value in VariableManager.instance.variables){

            if(vars === null){
                vars = Array();
            }

            let preexisting = false;

            vars.forEach((item)=>{
                if(item.property.name === this.selectedProperty.name){
                    item.key = value;
                    preexisting = true;
                }
            })

            if(!preexisting) {
                vars.push({
                    "property": this.selectedProperty,
                    "key": value
                });
            }

            this.component.setAttribute("variables",JSON.stringify(vars));

            VariableManager.instance.variableUpdateCallbacks.push({
                "id" : this.id
            })

            this.updateVariablesCallback();

        }else{
            let newVars = Array();

            if(vars !== null){
                vars.forEach((item)=>{
                    if(item.property.name !== this.selectedProperty.name){
                        newVars.push(item);
                    }
                })
            }
            this.component.setAttribute("variables",JSON.stringify(newVars));

            this._updatePropertyValue(this.selectedProperty,value);
        }
    }

    updateStyle(key,value){
        this.component.style.setProperty(key,value);
    }

    select(){

        console.log(this)

        this.component.style.borderColor = Editor.instance.selectedBorderColor;
        PropertyEditor.instance.updatePropertyInputSuggestions(this.properties);

        if(this.selectedProperty !== undefined){
            PropertyEditor.instance.selectProperty(this.selectedProperty.call);
        }else{
            PropertyEditor.instance.selectProperty(this.properties[0].call);
        }

        let parentNode = this.component.parentNode;

        this.component.classList.add("active-selection");

        PropertyEditor.instance.editPropertyInput.focus();

        parentNode.insertBefore(Editor.instance.addComponentButton,this.component.nextElementSibling);
    }

    getParent(){
        return this.component.parentNode;
    }

    setId(id){
        this.id = id;
        this.component.id = id;
    }

    clone(){

        let copy = Reflect.construct(Editor.instance.elements[this.component.nodeName],[this.component.cloneNode(true)]);
        copy.setId(Math.floor((Math.random() * 1000) + 1));

        VariableManager.instance.variableUpdateCallbacks.push({
            "id" : copy.id
        })


        return copy;
    }
}

class TextComponent extends Component{

    constructor(component) {
        super(component);

        this._addProperty(Component.properties.Text);
        this._addProperty(Component.properties.Color);
        this._addProperty(Component.properties.Alignment);

        this._addAllowedType(Component.types.String);
        this._addAllowedType(Component.types.Number);
    }

}

class Heading extends TextComponent{

    constructor(component) {
        super(component);
    }

}

class Image extends Component{
    constructor(component) {
        super(component);

        this._addProperty(Component.properties.Image);

        this._addProperty(Component.properties.Width);

        this._addProperty(Component.properties.Height);
    }

}

class Div extends Component{

    constructor(component) {
        super(component);

        this._addProperty(Component.properties.Alignment);
    }

}

class P extends TextComponent{

    constructor(component) {
        super(component);
    }


}

