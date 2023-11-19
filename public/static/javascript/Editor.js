class Action{

    static variableManagerRenderScriptEditor(){
        return VariableManager.instance.renderScriptEditor();
    }


}
class Model{

    component;
    id;
    shown = false;
    onToggle;

    constructor(properties) {

        let html = document.getElementById("model-template").innerHTML;

        Object.entries(properties).forEach(entry=>{
            const [key, value] = entry;
            html = html.replaceAll(key,value);
        })

        this.component = document.getElementById("model-template").cloneNode(true);

        this.component.innerHTML = html;
        this.id = properties["@id"];
        this.component.id = properties["@id"];
        this.onToggle = properties["@onToggle"];

        document.getElementById("model-template").after(document.getElementById("model-template"),this.component);
    }

    toggle(){

        if(this.onToggle !== undefined) {
            this.onToggle();
        }

        if(!this.shown){
            this.shown = true;
            this.component.style.display = "block";
        }else{
            this.component.style.display = "none";
            this.shown = false;
        }
    }
}
class Editor {

    static instance  = new Editor();

    hoveredComponent;
    selectedBorderColor = "aqua"
    selectedComponent;
    elements = {"H1": Heading,"H2":  Heading,"H3": Heading,"H4" :Heading,"H5": Heading,"H6": Heading, "IMG": Image,"DIV":Div, "P": P};
    components = Array;
    componentLocationSelector;
    copiedComponent;
    addComponentButton = document.getElementById("add-component-button");
    models = Array();
    propertyEditor;
    UIComponents;

    constructor() {
        document.addEventListener('mousemove', e => {
            this.updateHoverComponent(document.elementFromPoint(e.x+window.scrollX, e.y+window.screenY));
            this.hoveredComponent = document.elementFromPoint(e.x+window.scrollX, e.y+window.screenY);
        });

        document.addEventListener("click",e => {
            Editor.instance.selectComponentCallback(e);
            PropertyEditor.instance.onClickCallback();
        });


        this.componentLocationSelector =  document.getElementById("component-location-selector");


        this.models["addComponent"] = new Model({
            "@id":"addComponent",
            "@title":"Add New Component" ,
            "@actionButton" :"Add",
            "@cancelButton": "Cancel",
            "@actionCallback" : "console.log('Hello world!')",
        });

        this.models["deleteComponent"] = new Model({
            "@id":"deleteComponent",
            "@title":"Delete Conformation",
            "@body": "Are you sure you want to delete this component?",
            "@actionButton" :"Delete",
            "@cancelButton": "Cancel",
            "@actionCallback": "Editor.instance.deleteComponent()"
        });

        this.models["scriptEditor"] = new Model({
            "@id":"scriptEditor",
            "@title":"Script Editor" ,
            "@actionButton" :"Save",
            "@body": '<div id="scriptEditor-textarea" contenteditable="true"></div>',
            "@cancelButton": "Cancel",
            "@actionCallback" : "Editor.instance.saveScript()",
            "@onToggle" : function () {
                document.getElementById("scriptEditor-textarea").innerHTML = VariableManager.instance.renderScriptEditor();
            }
        });

    }


    saveScript(){
        let area = document.getElementById("scriptEditor-textarea");
        VariableManager.instance.loadScript(area.innerText);
        area.innerHTML = VariableManager.instance.renderScriptEditor();
    }

    updateHoverComponent(component){

        if(this.hoveredComponent === undefined || this.selectedComponent === undefined || component === undefined) {
            return
        }

        if(component.id === this.selectedComponent.id){
            return;
        }

        if(component.classList.contains("component")) {

            if (component !== this.hoveredComponent && this.hoveredComponent.id !== this.selectedComponent.id) {
                this.hoveredComponent.style.borderColor = "transparent";
            } else {
                component.style.borderColor = "red";
            }
        }

    }

    selectComponentCallback(){
        let selected = this.hoveredComponent;

        if(selected === undefined || selected === null){
            return;
        }

        if(!selected.classList.contains("component")){
            return;
        }

        if(!this.elements.hasOwnProperty(selected.nodeName)){
            console.log("[Editor] Component not registered with object class")
            return;
        }

        if(this.selectedComponent !== undefined){
            this.selectedComponent.updateStyle("border-color","transparent");
            this.selectedComponent.component.classList.remove("active-selection");
        }

        if(this.components[selected.id] === undefined){
            this.components[selected.id] = Reflect.construct(this.elements[selected.nodeName],[selected]);
        }


        this.selectedComponent = this.components[selected.id];
        this.selectedComponent.select();
    }

    moveComponent(direction){

        let component = this.selectedComponent.component;
        let parentNode = component.parentNode;

        if (direction === -1 && component.previousElementSibling) {
            parentNode.insertBefore(component, component.previousElementSibling);
        } else if (direction === 1 && component.nextElementSibling) {
            parentNode.insertBefore(component, component.nextElementSibling.nextElementSibling.nextElementSibling)
        }

        parentNode.insertBefore(Editor.instance.addComponentButton,component.nextElementSibling)
    }

    exportAsPDF(){
        let contents = document.getElementById("document").innerHTML;
        let printWindow = window.open('','','height=1684,width=1190');

        printWindow.document.write('<html><head><title>Div Contents</title></head><body>');
        printWindow.document.write("<style>"+document.getElementById("styleSheet").innerText+"</style>");
        printWindow.document.write(contents);
        printWindow.document.write('</body></html>');
        printWindow.print();
        printWindow.close();
    }

    saveComponent(){
        console.log("Saving component");
    }

    cutSelectedComponent(){

        this.copySelectedComponent();
        this.selectedComponent.component.remove();
        this.selectedComponent = undefined;
    }

    deleteComponent(){
        this.selectedComponent.component.remove();
        this.selectedComponent = undefined;
    }

    copySelectedComponent(){

        this.copiedComponent = this.selectedComponent.clone();

    }

    pasteSelectedComponent(){

        this.selectedComponent.component.after(this.selectedComponent.component,this.copiedComponent.component);
        this.components[this.copiedComponent.id] = this.copiedComponent;
        this.selectedComponent = this.components[this.copiedComponent.id];
        this.copiedComponent = undefined;
    }

    addNewComponent(){


    }
}

