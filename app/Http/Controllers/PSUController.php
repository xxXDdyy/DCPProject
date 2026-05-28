<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PSUController extends Controller
{
    public function welcome() {
        $currentDate = date('Y-m-d');
        return "<b>Welcome</b><br>
        Welcome Dyxx Peralta<br>
        Current Date: $currentDate";
    }
    
    public function mission() {
        $currentDate = date('Y-m-d');
        return "<b>Mission</b><br>
        The Pangasinan State University shall provide a human-centric,  resilient and sustainable academic environment to produce dynamic,<br> responsive and future-ready individuals capable of meeting the requirements of the local and global communities and industries<br>
        Current Date: $currentDate";
    }

    public function vision() {
        $currentDate = date('Y-m-d');
        return "<b>Vision</b><br> 
        To be a leading industry-driven state university in the ASEAN region by 2030<br>
        Current Date: $currentDate";
    }

    public function EOMSPolicy() {
        $currentDate = date('Y-m-d');
        return "<b>EOMSPolicy</b><br>
        The Pangasinan State University shall be recognized as an ASEAN premier state university that provides <br>quality education and satisfactory service delivery through instruction, research, extension and production.<br>
        We commit our expertise and resources to produce professionals who meet the expectations <br>of the industry and other interested parties in the national and international community.<br>
        We shall continuously improve our operations through systems and process innovations guided by ethical, <br>
        intellectual property and technology transfer standards in response to the changing educational, scientific <br>and technological developments for social responsiveness and in support of the institution’s strategic direction.<br>
        Current Date: $currentDate";
    }

    public function student($name, $course) {
        return "Hello, I am $name and I am taking $course.";
    }
}
