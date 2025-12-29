<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\PasswordHelper;
use Illuminate\Support\Facades\Hash;

class TempPasswordController extends Controller
{
    /**
     * Generate and return a temporary password
     */
    public function generateTempPassword()
    {
        $tempPassword = PasswordHelper::generateTempPassword();
        
        return response()->json([
            'success' => true,
            'temp_password' => $tempPassword
        ]);
    }
    
    /**
     * Set temporary password for user (for admin use)
     */
    public function setTempPassword(Request $request)
    {
        $tempPassword = PasswordHelper::generateTempPassword();
        
        // Update user password (replace with your user model)
        // User::where('id', $request->user_id)->update([
        //     'password' => Hash::make($tempPassword),
        //     'is_temp_password' => true
        // ]);
        
        return response()->json([
            'success' => true,
            'temp_password' => $tempPassword,
            'message' => 'Temporary password generated successfully'
        ]);
    }
}