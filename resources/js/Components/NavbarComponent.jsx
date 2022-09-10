import React from "react";
import { Link } from "@inertiajs/inertia-react";

const NavbarComponent = () => {
    return (
        <>
            <header>
                <nav className="h-20 flex items-center justify-between w-full py-4 md:py-0 px-4 text-lg text-gray-700 bg-teal-500">
                    <div className="nav-brand flex items-center gap-3 flex-1">
                        <img
                            src="/assets/logo.png"
                            alt="logo"
                            className="h-10 w-10"
                        />
                        <h1 className="text-poppins font-bold text-white">
                            WorkNote
                        </h1>
                    </div>

                    <div className="link-area flex w-full justify-between flex-1 text-poppins text-base font-semibold items-center">
                        <ul className="flex gap-5">
                            <li className="cursor-pointer text text-white">
                                <Link>Dashboard</Link>
                            </li>
                            <li className="cursor-pointer text-gray-200">
                                <Link>Projects</Link>
                            </li>
                        </ul>
                        <Link className="bg-white px-5 py-1 rounded-full text-teal-500">
                            Logout
                        </Link>
                    </div>
                </nav>
            </header>
        </>
    );
};

export default NavbarComponent;
