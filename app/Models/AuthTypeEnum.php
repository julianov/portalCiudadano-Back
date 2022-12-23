<?php

namespace App\Models;

enum AuthTypeEnum
{
	case REGISTRADO;
	case ANSES;
	case AFIP;
	case MIARGENTINA;
	case PRESENCIAL;
}
