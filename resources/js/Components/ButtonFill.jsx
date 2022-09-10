import React from "react";
import { Spinner } from "flowbite-react";

const ButtonFill = ({ text, type, loading, ...rest }) => {
    return (
        <>
            <button
                type={type}
                className={`text-md py-2 text-poppins text-white ${
                    loading
                        ? "bg-teal-500"
                        : "bg-teal-400 hover:bg-teal-500 smooth duration-200"
                } w-full flex gap-2 items-center justify-center rounded-md`}
                {...rest}
            >
                {loading ? <Spinner size="sm" /> : ""}
                {text}
            </button>
        </>
    );
};

export default ButtonFill;
