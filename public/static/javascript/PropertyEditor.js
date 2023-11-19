class PropertyEditor {

    editPropertyInput = document.getElementById("property-editor")
    editPropertyDropdown = document.getElementById("property-editor-selection-suggestion");

    selectPropertyDropdown = document.getElementById("property-dropdown-selector");
    selectPropertyInput = document.getElementById("property-dropdown-input");

    static instance = new PropertyEditor();

    constructor() {
        Editor.instance.propertyEditor = this;
        this.editPropertyInput.onfocus = function () {
            PropertyEditor.instance.editPropertyDropdown.style.display = "flex";
        }

        this.editPropertyDropdown.style.width = window.getComputedStyle(this.editPropertyInput, "width").getPropertyValue("width");


        this.editPropertyInput.addEventListener('keyup', e =>{
            Editor.instance.selectedComponent.setProperty(PropertyEditor.instance.editPropertyInput.value);
            this.updatePropertyInputSuggestions(Editor.instance.selectedComponent.properties);
            e.stopImmediatePropagation();
        });

        this.selectPropertyInput.addEventListener('keyup',e =>{

            if(e.key === "Enter"){
                this.selectProperty(this.selectPropertyInput.value)
            }

            this.updatePropertyInputSuggestions(Editor.instance.selectedComponent.properties);
        })
    }

    onClickCallback(){

        if(document.activeElement === this.selectPropertyInput){
            this.selectPropertyDropdown.style.display = "flex";
            this.editPropertyDropdown.style.display = "none"
        }

        if(document.activeElement === this.editPropertyInput){
            this.selectPropertyDropdown.style.display = "none";
            this.editPropertyDropdown.style.display = "flex"
        }
    }

    updateEditInputSuggestions(){

        let input = this.editPropertyInput.value;
        this.editPropertyDropdown.innerHTML = "";

        if(input.startsWith("@",0)) {

            let variables = VariableManager.instance.variables;

            Object.entries(variables).forEach(entry => {

                let [key, value] = entry;

                if (key.includes(input)) {
                    let element = document.createElement("a");
                    element.setAttribute('href', 'javascript:PropertyEditor.instance.selectSuggestion("'+key+'")');
                    element.innerHTML = key + " (" + value.value + ")"

                    this.editPropertyDropdown.appendChild(element)
                }
            })
        }
    }

    selectSuggestion(suggestion){
        this.editPropertyInput.value =  suggestion;
        Editor.instance.selectedComponent.setProperty(suggestion);
        this.editPropertyDropdown.style.display = "none";
    }

    updatePropertyInputSuggestions(properties){

        properties.sort((a, b) => a.type.localeCompare(b.type));
        let outcome = false;

        this.selectPropertyDropdown.innerHTML = "";

        for(let property of properties){

            if(property.name.includes(this.selectPropertyInput.value) && this.selectPropertyInput.value !== null) {

                let element = document.createElement("a");
                element.setAttribute('href', 'javascript:PropertyEditor.instance.selectProperty("' + property.call + '")');
                element.innerHTML = Component.propertyCategories[property.type] + " " + property.name

                this.selectPropertyDropdown.appendChild(element)

                outcome = true;
            }
        }

        if(!outcome){
            let element = document.createElement("p");
            element.innerText = "No results found";
            this.selectPropertyDropdown.appendChild(element)
        }
    }

    selectProperty(call){

        let outcome = false;

        for(let property of Editor.instance.selectedComponent.properties){

            if(property.call === call || property.name === call){

                this.selectPropertyInput.value = Component.propertyCategories[property.type]+" "+property.name;
                this.editPropertyInput.value = Editor.instance.selectedComponent.getProperty(property);
                this.selectPropertyDropdown.style.display = "none"
                Editor.instance.selectedComponent.selectedProperty = property;
                outcome = true;
                break;
            }
        }
    }


}