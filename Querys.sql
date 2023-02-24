create table "ciudadanos"
(
    "id"         uuid                           not null,
    "prs_id"     varchar(255)                   not null,
    "cuil"       varchar(255)                   not null,
    "password"   varchar(255)                   not null,
    "nombre"     varchar(255)                   not null,
    "apellido"   varchar(255)                   not null,
    "created_at" timestamp(0) without time zone null,
    "updated_at" timestamp(0) without time zone null,
    "deleted_at" timestamp(0) without time zone null
);
alter table "ciudadanos"
    add primary key ("id");
alter table "ciudadanos"
    add constraint "ciudadanos_prs_id_unique" unique ("prs_id");
alter table "ciudadanos"
    add constraint "ciudadanos_cuil_unique" unique ("cuil");
create table "datos_contacto"
(
    "id"               uuid                           not null,
    "ciudadano_id"     uuid                           not null,
    "email"            varchar(255)                   not null,
    "email_token"      varchar(255)                   not null,
    "fecha_nacimiento" varchar(255)                   not null,
    "celular"          varchar(255)                   not null,
    "departamento_id"  varchar(255)                   not null,
    "localidad_id"     varchar(255)                   not null,
    "domicilio"        varchar(255)                   not null,
    "numero"           varchar(255)                   not null,
    "created_at"       timestamp(0) without time zone null,
    "updated_at"       timestamp(0) without time zone null,
    "deleted_at"       timestamp(0) without time zone null
);
alter table "datos_contacto"
    add constraint "datos_contacto_ciudadano_id_foreign" foreign key ("ciudadano_id") references "ciudadanos" ("id") on delete cascade;
alter table "datos_contacto"
    add primary key ("id");
alter table "datos_contacto"
    add constraint "datos_contacto_email_unique" unique ("email");
create table "autenticacion_tipos"
(
    "id"          uuid                                                                                               not null,
    "descripcion" varchar(255) check ("descripcion" in ('REGISTRADO', 'ANSES', 'AFIP', 'MIARGENTINA',
                                                        'PRESENCIAL'))                                               not null,
    "created_at"  timestamp(0) without time zone                                                                     null,
    "updated_at"  timestamp(0) without time zone                                                                     null,
    "deleted_at"  timestamp(0) without time zone                                                                     null
);
alter table "autenticacion_tipos"
    add primary key ("id");
create table "ciudadano_autenticacion"
(
    "id"                    uuid                           not null,
    "ciudadano_id"          uuid                           not null,
    "autenticacion_tipo_id" uuid                           not null,
    "nivel"                 varchar(255)                   not null,
    "fecha_autenticacion"   timestamp(0) without time zone not null,
    "created_at"            timestamp(0) without time zone null,
    "updated_at"            timestamp(0) without time zone null
);
alter table "ciudadano_autenticacion"
    add constraint "ciudadano_autenticacion_ciudadano_id_foreign" foreign key ("ciudadano_id") references "ciudadanos" ("id") on delete cascade;
alter table "ciudadano_autenticacion"
    add constraint "ciudadano_autenticacion_autenticacion_tipo_id_foreign" foreign key ("autenticacion_tipo_id") references "autenticacion_tipos" ("id") on delete cascade;
alter table "ciudadano_autenticacion"
    add primary key ("id");
create table "presencial"
(
    "ciudadano_autenticacion_id" uuid                           not null,
    "dni_frente"                 varchar(255)                   not null,
    "dni_dorso"                  varchar(255)                   not null,
    "foto"                       varchar(255)                   not null,
    "actor_id"                   varchar(255)                   not null,
    "fecha_autenticacion"        date                           not null,
    "created_at"                 timestamp(0) without time zone null,
    "updated_at"                 timestamp(0) without time zone null
);
alter table "presencial"
    add constraint "presencial_ciudadano_autenticacion_id_foreign" foreign key ("ciudadano_autenticacion_id") references "ciudadano_autenticacion" ("id");
