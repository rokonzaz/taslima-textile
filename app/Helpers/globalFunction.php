<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

function getAssetVersion(){
    return $version="v1.03";
}

if (!function_exists('userCan')) {
    function userCan($permission)
    {
        if(getUserRole()=='super-admin') return true;
        return auth()->check() && auth()->user()->permissions->pluck('name')->contains($permission);
    }
}
function userPermissions()
{
    return auth()->check() ? auth()->user()->permissions->pluck('name')->toArray() : [];
}
function getUserRole($option = 'slug') {
    // Check if user is authenticated
    if (Auth::check()) {
        $user = Auth::user();
        // Check if the user has a role
        if ($user->role) {
            $role = $user->role;
            // Return role information based on the requested option
            switch ($option) {
                case 'id':
                    return $role->id;
                    break;
                case 'name':
                    return $role->name;
                    break;
                case 'slug':
                    return $role->slug;
                    break;
                case 'created_by':
                    return $role->created_by;
                    break;
                // Add more cases for other options as needed
                default:
                    return null;
                    break;
            }
        } else {
            // Handle case where user does not have a role
            return null;
        }
    } else {
        // Handle case where user is not authenticated
        return null;
    }
}
function getUserEmpId()
{
    return auth()->user()->emp_id;
}
function getManagableEployees()
{
    return auth()->user()->manageableEmployees;
}
function getManagableEployeeIDs()
{
    return getManagableEployees()->pluck('emp_id');
}
function isManagableEployeesAvailable()
{
    return count(getManagableEployees())>0;
}

function isRoleIn($roleArray)
{
    if(gettype($roleArray)!='array') return false;
    return in_array(getUserRole(), $roleArray);
}

function generateRandomString($length = 10) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}

function formatCarbonDate($dateTime, $format='date')
{
    if($dateTime!='' && gettype($dateTime)=='string'){
        $dt=Carbon::parse($dateTime);

        if($format=='time')
            return $dt->format('h:i A');
        elseif($format=='timeWithSec')
            return $dt->format('h:i:s A');
        elseif($format=='datetime')
            return $dt->format('M d, Y h:i A');
        elseif($format=='datetimeWithSec')
            return $dt->format('M d, Y h:i:s A');
        else
            return $dt->format('M d, Y');
    }else{
        return null;
    }

}


function strongPasswordGenerator( $length = 12 ) {
    return "N3xHRM@".generateRandomString($length-6);
}

function employeeDefaultProfileImage($gender='Male')
{
    if($gender=='Female') return asset('assets/img/default/default-emp-female.jpg');
    else return asset('assets/img/default/default-emp-male.jpg');
}
function employeeProfileImage($emp_id, $fileName)
{
    return asset("uploads/employees/$emp_id/profile/$fileName");
}
function employeeDigitalSignature($emp_id, $fileName)
{
    return asset("uploads/employees/$emp_id/signature/$fileName");
}

function defaultImage($a)
{
    if($a=='department') return asset('assets/img/default/default-department.jpg');
    else return asset('assets/img/default/logo_new.svg');
}

function getFileType($fileName) {
    // Get the file extension
    $extension = pathinfo($fileName, PATHINFO_EXTENSION);

    // Determine the file type based on the extension
    switch (strtolower($extension)) {
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
        case 'bmp':
            return 'Image';
        case 'txt':
        case 'doc':
        case 'docx':
        case 'pdf':
        case 'rtf':
            return 'Document';
        case 'mp3':
        case 'wav':
        case 'flac':
        case 'aac':
            return 'Audio';
        case 'mp4':
        case 'avi':
        case 'mkv':
        case 'mov':
            return 'Video';
        case 'zip':
        case 'rar':
        case '7z':
        case 'tar':
            return 'Archive';
        default:
            return 'Unknown';
    }
}


function tripleBase64Encode($data) {
    return base64_encode(base64_encode(base64_encode($data)));
}

function tripleBase64Decode($data) {
    return base64_decode(base64_decode(base64_decode($data)));
}

function dateToDateCount($startDate, $endDate)
{
    $startDate = Carbon::parse($startDate);
    $endDate = Carbon::parse($endDate);
    return $daysDifference = $endDate->diffInDays($startDate)+1;
}


function minutesToHour($minuets)
{
    $hours = floor($minuets / 60); // Integer division for full hours
    $minutes = $minuets % 60; // Remaining minutes after full hours

    // Display format based on duration
    if ($hours > 0) {
        return "{$hours}h {$minutes}min";
    } else {
        return "{$minutes}min";
    }
}
function getAuthEmpId()
{
    return auth()->user()->emp_id;
}
function getManageableEmployees($a='')
{
    if($a=='as-line-manager') return $manageableEmployees=auth()->user()->lineManagerEmployees;
    if($a=='as-department-head') return $manageableEmployees=auth()->user()->departmentHeadEmployees;
    return $manageableEmployees=auth()->user()->manageableEmployees;
}

function getManageableEmployeesIDs($a='')
{
    $manageableEmployees=getManageableEmployees($a);
    return $manageableEmployeesIDs = $manageableEmployees->pluck('emp_id');
}


function getFirstWord($string)
    {
        $trimmedString = trim($string);
        if (empty($trimmedString)) {
            return '';
        }
        $words = explode(' ', $trimmedString);
        return $words[0];
    }

?>


