<?php

/**
 * Sanitize data for security purpose.  
 * @param Array|String   $fields  Fields to be compared.     
 * @author Fábio Sant'Ana <fabio@4comtec.com.br>
 * @return $data Array|String Return sanitized content.
 * 
*/ 
function sanitizeData($data) {

    if (is_array($data)) {
        array_walk_recursive($data, function(&$item, $key) {
            $item = addslashes($item);
        });
    }
    else {
        addslashes($data);
    }

    return $data;
    
}

/**
 * get information about a list of user.  
 * @param Array   $idUserList  Array with one or more idUser's.     
 * @author Fábio Sant'Ana <fabio@4comtec.com.br>
 * @return $userListRet Collection with users data.
 * 
*/ 
function getUserInfo(array $idUserList) {

    $userListRet = [];

    $userModel = new \App\Models\UserModel;

    foreach ($idUserList as $key => $idUser) {

        $user = $userModel::find($idUser);

        if ($user->typeUser == 'person') {

            array_push($userListRet, $user->person);  

        } else {

            array_push($userListRet, $user->company);   

        }       

    }

    return $userListRet;
    
}

