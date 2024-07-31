import{D as r,o as c,f as p,b as e,a as f,i as l,E as u,l as _}from"./app-XwlNAViC.js";import{_ as m}from"./_plugin-vue_export-helper-DlAUqK2U.js";const h={data(){return{reading:null,read_at:null,type:"fbs",options:{inline:!1,sideBySide:!0,format:"YYYY-MM-DD HH:mm:ss",icons:{time:"far fa-clock",date:"fas fa-calendar-alt",up:"fa fa-arrow-up",down:"fa fa-arrow-down",previous:"fa fa-chevron-left",next:"fa fa-chevron-right",today:"far fa-clock",clear:"fas fa-trash-alt"}}}},methods:{save(){let a={reading:this.reading,read_at:this.read_at,type:this.type};axios.post("api/v1/readings",a).then(t=>{t.data.reading,this.$router.push("/")}).catch(t=>{console.log(t.response.data),t.response.status==415&&t.response.data.details.forEach(n=>{console.log(n)})})}},created(){},mounted(){document.getElementById("dateInput").focus()}},v={class:"container"},g={class:"row justify-content-center"},b={class:"col-md-8"},y={class:"card"},I=e("div",{class:"card-header"},"Blood Sugar Reading",-1),k={class:"card-body"},x={class:"mb-3"},V=e("label",{for:"dateInput",class:"form-label"},"Date",-1),w={class:"mb-3"},B=e("label",{for:"typeInput",class:"form-label"},"Type",-1),D=e("option",{value:"fbs"},"Fasting",-1),R=e("option",{value:"rbs"},"Random",-1),E=[D,R],M={class:"mb-3"},S=e("label",{for:"readingInput",class:"form-label"},"Reading",-1),Y={class:"card-footer"};function U(a,t,n,C,s,d){const i=r("date-picker");return c(),p("div",null,[e("div",v,[e("div",g,[e("div",b,[e("div",y,[I,e("div",k,[e("div",x,[V,f(i,{id:"dateInput",modelValue:s.read_at,"onUpdate:modelValue":t[0]||(t[0]=o=>s.read_at=o),config:s.options},null,8,["modelValue","config"])]),e("div",w,[B,l(e("select",{class:"form-control",name:"typeInput",id:"typeInput","onUpdate:modelValue":t[1]||(t[1]=o=>s.type=o)},E,512),[[u,s.type]])]),e("div",M,[S,l(e("input",{type:"text",class:"form-control",name:"readingInput",id:"readingInput","aria-describedby":"helpId",placeholder:"Reading","onUpdate:modelValue":t[2]||(t[2]=o=>s.reading=o)},null,512),[[_,s.reading]])])]),e("div",Y,[e("button",{type:"button",class:"btn btn-primary",onClick:t[3]||(t[3]=(...o)=>d.save&&d.save(...o))}," Save ")])])])])])])}const T=m(h,[["render",U]]);export{T as default};
