class VariableManager
{
    variables = Array();
    currentScript;
    variableUpdateCallbacks = Array();

    static instance = new VariableManager();

    constructor() {
        let defaultScript = "";
        this.loadScript(defaultScript);
    }

    runUpdateCallbacks(){
        this.variableUpdateCallbacks.forEach((callback)=>{
            Editor.instance.components[callback.id].updateVariablesCallback();
        })
    }

    addVariable(key,value,type){
        this.variables[key] = {value,type};
    }

    getVariable(key){
        return this.variables[key].value;
    }

    loadScript(script){
        this.currentScript = script;

        let entries = script.split(";");

        entries.forEach((n)=>{

            n = n.trim();

            if(n !== null && n !== undefined && n !== ""){

                let split = n.split("=");

                let key = split[0].trim().split(" ");
                let value = split[1].trim();

                this.addVariable(key[1],value,key[0]);
            }
        });

     this.runUpdateCallbacks();
    }

    renderScriptEditor(){

        let output = "";
        Object.entries(this.variables).forEach(entry=>{

            let [key,value] = entry;
            let valueColor = "#55a902";
            let keyColor = "#c43156";

            if(!isNaN(parseInt(value.value))){
                valueColor = "#b44da1"
            }

            output += "<span style='color:#4197c1; font-weight: bolder'>"+value.type+"</span> <span style='color:"+keyColor+"'>"+key+"</span> = <span style='color: "+valueColor+"'>"+value.value+"</span>;<br>";
        })

        return output;
    }
}