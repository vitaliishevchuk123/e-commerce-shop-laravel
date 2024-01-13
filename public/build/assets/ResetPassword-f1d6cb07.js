import{T as c,c as f,w as n,o as w,a as o,u as e,Z as _,b as a,d as g,e as b,n as y}from"./app-d834ddb5.js";import{_ as l,a as i,b as m}from"./TextInput-f82f2f12.js";import{P as V}from"./PrimaryButton-38797844.js";import{M as v}from"./MainLayout-3b79e168.js";import"./_plugin-vue_export-helper-c27b6911.js";const x={class:"flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100"},k={class:"w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"},h=["onSubmit"],P={class:"mt-4"},q={class:"mt-4"},B={class:"flex items-center justify-end mt-4"},$={__name:"ResetPassword",props:{email:{type:String,required:!0},token:{type:String,required:!0}},setup(u){const d=u,s=c({token:d.token,email:d.email,password:"",password_confirmation:""}),p=()=>{s.post(route("password.store"),{onFinish:()=>s.reset("password","password_confirmation")})};return(S,t)=>(w(),f(v,null,{default:n(()=>[o(e(_),{title:"Reset Password"}),a("div",x,[a("div",k,[a("form",{onSubmit:g(p,["prevent"])},[a("div",null,[o(l,{for:"email",value:"Email"}),o(i,{id:"email",type:"email",class:"mt-1 block w-full",modelValue:e(s).email,"onUpdate:modelValue":t[0]||(t[0]=r=>e(s).email=r),required:"",autofocus:"",autocomplete:"username"},null,8,["modelValue"]),o(m,{class:"mt-2",message:e(s).errors.email},null,8,["message"])]),a("div",P,[o(l,{for:"password",value:"Password"}),o(i,{id:"password",type:"password",class:"mt-1 block w-full",modelValue:e(s).password,"onUpdate:modelValue":t[1]||(t[1]=r=>e(s).password=r),required:"",autocomplete:"new-password"},null,8,["modelValue"]),o(m,{class:"mt-2",message:e(s).errors.password},null,8,["message"])]),a("div",q,[o(l,{for:"password_confirmation",value:"Confirm Password"}),o(i,{id:"password_confirmation",type:"password",class:"mt-1 block w-full",modelValue:e(s).password_confirmation,"onUpdate:modelValue":t[2]||(t[2]=r=>e(s).password_confirmation=r),required:"",autocomplete:"new-password"},null,8,["modelValue"]),o(m,{class:"mt-2",message:e(s).errors.password_confirmation},null,8,["message"])]),a("div",B,[o(V,{class:y({"opacity-25":e(s).processing}),disabled:e(s).processing},{default:n(()=>[b(" Reset Password ")]),_:1},8,["class","disabled"])])],40,h)])])]),_:1}))}};export{$ as default};
