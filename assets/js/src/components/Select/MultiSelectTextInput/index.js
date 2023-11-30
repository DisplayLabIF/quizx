import React, { useState,useEffect } from 'react';
import CreatableSelect from 'react-select/creatable';

function MultiSelectTextInput({value, setValue, placeholder, onBlur}) {
    const components = {
        DropdownIndicator: null,
    };
    const [inputValue, setInputValue] = useState('');
    const [selectValue, setSelectValue] = useState([]);

    const createOption = (label) => ({
        label,
        value: label,
    });

    function handleChange (target) {
        let selectValueAux = [...selectValue];

        if(!target || target.length === 0){
            selectValueAux = [];
        }else{
            selectValueAux = target.map((targetValue) =>({
                label:targetValue.value,
                value: targetValue.value
            }))
        }
        setSelectValue(selectValueAux);
        setValue(
            selectValueAux.map((selectValu) =>(selectValu.value))
        );
    }

    function handleInputChange (inputValue) {
        setInputValue(inputValue);
    };

    function handleKeyDown(event) {
        const inputValueAux = inputValue;
        const  selectValueAux = selectValue;
        if (!inputValueAux) return;
        switch (event.key) {
            case 'Enter':
            case ',':
                setInputValue('');
                setSelectValue([...selectValueAux, createOption(inputValueAux)]);
                setValue([...value, inputValueAux]);
                event.preventDefault();
        }
    }

    function onBlurSetValue(event) {
        const inputValueAux = inputValue;
        const  selectValueAux = selectValue;
        if (!inputValueAux){
            onBlur();
            return;    
        }
        setInputValue('');
        setSelectValue([...selectValueAux, createOption(inputValueAux)]);
        setValue([...value, inputValueAux]);
        event.preventDefault();
        onBlur();
    }

    // useEffect(()=>{
    //     setValue(
    //         selectValue.map((selectValueAux) =>(selectValueAux.value))
    //     );
    // },[selectValue]);

    useEffect(()=>{
        if(value.length > 0){
            setSelectValue(
                value.map((valueAux) =>(      
                    createOption(valueAux)
                ))
            );
        }
    },[value]);

return (
    <CreatableSelect
        components={components}
        inputValue={inputValue}
        isClearable
        isMulti
        menuIsOpen={false}
        onChange={handleChange}
        onInputChange={handleInputChange}
        onKeyDown={handleKeyDown}
        placeholder={placeholder}
        value={selectValue}
        styles={{control: (provided, state) => ({
            ...provided,
            width: 100+'%',
            minHeight: 50,
            borderRadius: 4,
            boxShadow: state.isFocused ? '0px 2px 4px rgba(0, 0, 0, 0.1)' : '0 4px 4px rgba(0, 0, 0, 0.1)',
            '&:hover': {
                border: state.isFocused && '1px solid var(--primary)'
            },
            border: state.isFocused ? '1px solid var(--primary)' : '1px solid #dadada',
            borderColor: state.isFocused && '#80bdff',
            outline: state.isFocused && 0,
            WebkitBoxShadow: state.isFocused && '0px 2px 4px rgba(0, 0, 0, 0.1)'
          })}}
        onBlur={onBlurSetValue}
    />);
}

export default MultiSelectTextInput;