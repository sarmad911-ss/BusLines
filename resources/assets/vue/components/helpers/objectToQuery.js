'use strict';

function isFormData(value){
    return value instanceof FormData
}

function isArray(value) {
    return Array.isArray(value);
}

function isObject(value){
    if(isNull(value) || isUndefined(value))
        return false;
    if(isFile(value))
        return false;
    return typeof value === "object";
}

function isNull(value) {
    return value === null;
}

function isUndefined(value) {
    return typeof value === 'undefined'
}

function isDate(value) {
    return value instanceof Date;
}

function isFile(value) {
    if(isNull(value) || isUndefined(value))
        return false;
    return value.constructor.name==="File";
}

function toUrl(object){
    list = [];
    convert(object);
    let items = [];
    list.forEach((item)=>{
        if(!isFile(item.value))
            items.push(`${item.key}=${item.value}`);
    });
    return items.join("&");
}

function toFormData(object){
    list = [];
    convert(object);
    let formdata = new FormData();
    list.forEach((item)=>{
        formdata.append(item.key,item.value);
    });
    return formdata;
}

var list = [];

function convert(object,prefix = ''){
    if(isObject(object)) {
        Object.keys(object).forEach((key)=>{
            if(isArray(object[key]) || isObject(object[key])) {
                convert(object[key], prefix === '' ? key : `${prefix}[${key}]`);
            }else
            if(!isNull(object[key]) && !isUndefined(object[key]))
            {
                if(prefix  === '') {
                    list.push({
                        key: key,
                        value: object[key],
                    });
                }
                else
                    list.push({
                        key: `${prefix}[${key}]`,
                        value: object[key],
                    });
            }
        })
    }
}

export {toFormData,toUrl};
