import React from "react";

const InputWithLabels = ({ label, type, name, placeholder, ...rest }) => {
    return (
        <>
            <label htmlFor={name} className="text-md text-gray-500 font-bold">
                {label}
            </label>
            <input
                type={type}
                className="border border-gray-400 bg-white text-md rounded-md drop-shadow-sm mt-2"
                placeholder={placeholder}
                autoComplete="off"
                id={name}
                name={name}
                {...rest}
            />
        </>
    );
};

export default InputWithLabels;
