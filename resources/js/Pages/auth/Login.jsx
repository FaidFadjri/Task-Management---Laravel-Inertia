import React from "react";
import InputWithLabels from "@/Components/InputWithLabels";
import Gap from "@/Components/Gap";
import ButtonFill from "@/Components/ButtonFill";
import { useState } from "react";
import axios from "axios";
import Swal from "sweetalert2";
import { Inertia } from "@inertiajs/inertia";

const Login = () => {
    const [isLoading, setIsLoading] = useState(false);
    const [values, setValues] = useState({
        email: "",
        password: "",
    });

    const handleChange = (e) => {
        const key = e.target.id;
        const value = e.target.value;
        setValues((values) => ({
            ...values,
            [key]: value,
        }));
    };

    const submit = (e) => {
        e.preventDefault();
        setIsLoading(true);
        axios
            .post(route("login"), values)
            .then(function (response) {
                Swal.fire(
                    "Congratulation!",
                    "Session has been save",
                    "success"
                ).then(() => {
                    location.href = route('dashboard');
                });
                setIsLoading(false);
            })
            .catch(function (error) {
                Swal.fire({
                    title: "Error!",
                    html: `<h1><strong>${
                        error.response.data.message
                    }</h1></strong> <p> ${
                        error.response.data.error_code
                            ? error.response.data.error_code
                            : ""
                    }`,
                    icon: "error",
                });
                setIsLoading(false);
            });
    };

    return (
        <>
            <section className="h-screen bg-teal-400 flex items-center justify-center">
                <main className="flex flex-col md:flex-row h-full w-full justify-center items-center md:h-3/4 md:w-3/4 drop-shadow-sm bg-white rounded-lg md:justify-between">
                    <div className="img-wrapper h-1/2 w-full md:h-full md:w-1/2 flex items-center justify-center bg-gray-100 rounded-b-lg md:rounded-lg">
                        <img
                            src="/assets/people-with-computers.png"
                            alt="thumbnail"
                            className="h-56 object-contain"
                        />
                    </div>
                    <div className="form-wrapper h-full w-full px-10 md:px-0 md:w-1/2 flex items-center justify-center rounded-lg bg-white">
                        <form
                            action="login"
                            className="flex flex-col w-96 text-poppins"
                            method="post"
                            onSubmit={submit}
                        >
                            <div className="form-head flex items-center gap-3">
                                <img
                                    src="/assets/logo.png"
                                    alt="logo"
                                    className="h-10 w-10 object-contain"
                                />
                                <div className="text-area">
                                    <h1 className="text-1xl md:text-2xl font-bold text-poppins text-gray-600">
                                        WorkNotes Apps
                                    </h1>
                                    <p className="text-gray-400 text-sm text-poppins">
                                        Easy manage your project & activity
                                    </p>
                                </div>
                            </div>
                            <Gap height={30} />
                            <InputWithLabels
                                label="Email atau Username"
                                type="Text"
                                name="email"
                                placeholder="username atau email"
                                value={values.email}
                                onChange={handleChange}
                            />
                            <Gap height={10} />
                            <InputWithLabels
                                label="Password"
                                type="password"
                                name="password"
                                placeholder="password"
                                value={values.password}
                                onChange={handleChange}
                            />
                            <Gap height={17} />
                            {isLoading ? (
                                <ButtonFill
                                    text="prosessing"
                                    type="submit"
                                    loading="true"
                                    disabled
                                />
                            ) : (
                                <ButtonFill text="login" type="submit" />
                            )}
                            <a
                                href=""
                                className="text-sm self-center mt-4 text-sky-700"
                            >
                                Need Help ?
                            </a>
                        </form>
                    </div>
                </main>
            </section>
        </>
    );
};

export default Login;
