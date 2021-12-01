<?php

namespace App\Response;

abstract class EnumMessageResponseType
{
    const Alert = "Alert";
    const Warning = "Warning";
    const Error = "Error";    
    const Danger = "Danger";
    const Success = "Success";
    const Exception = "Exception";
    const BusinessException = "BusinessException";
}