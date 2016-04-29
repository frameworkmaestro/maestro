<?php

return array(
    'fnbr20' => array('mainPanel', 'fnbr20/main/main', 'fnbrIconForm', '', A_ACCESS, array(
            'admin' => array('Admin', 'fnbr20/admin/main', 'fa fa-cogs fa16px', 'ADMIN', A_ACCESS, array(
                    'annostatus' => array('Anno Status', 'fnbr20/admin/annostatus/main', 'fa fa-cog fa16px', 'ADMIN', A_ACCESS, array()),
                    'domain' => array('Domain', 'fnbr20/admin/domain/main', 'fa fa-cog fa16px', 'ADMIN', A_ACCESS, array()),
                    'relationgroup' => array('Relation Group', 'fnbr20/admin/relationgroup/main', 'fa fa-cog fa16px', 'ADMIN', A_ACCESS, array()),
                    'type' => array('Type', 'fnbr20/admin/type/main', 'fa fa-cog fa16px', 'ADMIN', A_ACCESS, array()),
                    'genre' => array('Genre', 'fnbr20/admin/genre/main', 'fa fa-cog fa16px', 'ADMIN', A_ACCESS, array()),
                )),
            'auth' => array('Auth', 'fnbr20/main/auth', 'fa fa-key fa16px', 'ADMIN', A_ACCESS, array(
                    'persons' => array('Persons', 'fnbr20/auth/person/main', 'fa fa-child fa16px', 'ADMIN', A_ACCESS, array()),
                    'users' => array('Users', 'fnbr20/auth/user/main', 'fa fa-user fa16px', 'ADMIN', A_ACCESS, array()),
                )),
            'structure' => array('Structure', 'fnbr20/main/structure', 'fa fa-database fa16px', '', A_ACCESS, array(
                    'framestructure' => array('Frame', 'fnbr20/structure/frame/main', 'fa fa-list-alt fa16px', '', A_ACCESS, array()),
                    'corpusstructure' => array('Corpus', 'fnbr20/structure/corpus/main', 'fa fa-th-large fa16px', '', A_ACCESS, array()),
                    'cxnstructure' => array('Construction', 'fnbr20/structure/cxn/main', 'fa fa-th fa16px', '', A_ACCESS, array()),
                    'templatestructure' => array('Template', 'fnbr20/structure/template/main', 'fa fa-files-o fa16px', '', A_ACCESS, array()),
                    'semantictypestructure' => array('Semantic Type', 'fnbr20/structure/semantictype/main', 'fa fa-life-ring fa16px', '', A_ACCESS, array()),
                    'relationgroupstructure' => array('Relation Group', 'fnbr20/structure/relationgroup/main', 'fa fa-sitemap fa16px', '', A_ACCESS, array()),
                    'relationtypestructure' => array('Relation Type', 'fnbr20/structure/relationtype/main', 'fa fa-crosshairs fa16px', '', A_ACCESS, array()),
                    'layergroupstructure' => array('Layer Group', 'fnbr20/structure/layergroup/main', 'fa fa-sitemap fa16px', '', A_ACCESS, array()),
                    'layertypestructure' => array('Layer Type', 'fnbr20/structure/layertype/main', 'fa fa-crosshairs fa16px', '', A_ACCESS, array()),
                )),
            'annotation' => array('Annotation', 'fnbr20/annotation/main', 'fa fa-align-justify fa16px', '', A_ACCESS, array(
                    'lexicalAnnotation' => array('Frame Mode', 'fnbr20/annotation/main/formLexicalAnnotation', 'fa fa-file-text-o fa16px', '', A_ACCESS, array()),
                    'cnxAnnotation' => array('Construction Mode', 'fnbr20/annotation/main/formConstructionalAnnotation', 'fa fa-th fa16px', '', A_ACCESS, array()),
                    'corpusAnnotation' => array('Corpus Mode', 'fnbr20/annotation/main/formCorpusAnnotation', 'fa fa-file-text fa16px', '', A_ACCESS, array()),
                    'layers' => array('Manage Layers', '^fnbr20/annotation/layer/formManager', 'fa fa-list fa16px', 'JUNIOR', A_ACCESS, array()),
                )),
            'reports' => array('Reports', 'fnbr20/main/reports', 'fa fa-newspaper-o fa16px', '', A_ACCESS, array(
                   'framereport' => array('Frames', 'fnbr20/report/frame/main', 'fa fa-list-alt fa16px', '', A_ACCESS, array()),
                   'cxnreport' => array('Constructions', 'fnbr20/report/cxn/main', 'fa fa-th fa16px', '', A_ACCESS, array()),
                )),
            'grapher' => array('Grapher', 'fnbr20/main/grapher', 'fa fa-share-alt fa16px', '', A_ACCESS, array(
                    'fullgrapher' => array('Frames & CxN', 'fnbr20/grapher/grapher/main', 'fa fa-link fa16px', '', A_ACCESS, array()),
                )),
            'editor' => array('Editor', 'fnbr20/main/visualeditor', 'fa fa-arrows fa16px', '', A_ACCESS, array(
                    'frameeditor' => array('Frame Relation', 'fnbr20/visualeditor/frame/main', 'fa fa-list-alt fa16px', '', A_ACCESS, array()),
                    'corenesseditor' => array('Coreness', 'fnbr20/visualeditor/frame/coreness', 'fa fa-th-list fa16px', '', A_ACCESS, array()),
                    'cxneditor' => array('CxN Relation', 'fnbr20/visualeditor/cxn/main', 'fa fa-list-alt fa16px', '', A_ACCESS, array()),
                    'cxnframeeditor' => array('CxN-Frame Relation', 'fnbr20/visualeditor/cxnframe/main', 'fa fa-list-alt fa16px', '', A_ACCESS, array()),
                )),
            'utils' => array('Utils', 'fnbr20/main/utils/', 'fa fa-briefcase fa16px', '', A_ACCESS, array(
                    'importWSDoc' => array('Import WS/Documen', 'fnbr20/utils/import/formImportWSDoc', 'fa fa-upload fa16px', '', A_ACCESS, array()),
                    'exportDocWf' => array('Export Doc-Wf', 'fnbr20/utils/export/formExportDocWf', 'fa fa-download fa16px', '', A_ACCESS, array()),
                    'importLexWf' => array('Import Wf-Lexeme', 'fnbr20/utils/import/formImportLexWf', 'fa fa-upload fa16px', '', A_ACCESS, array()),
                    'importMWE' => array('Import MWE', 'fnbr20/utils/import/formImportMWE', 'fa fa-upload fa16px', '', A_ACCESS, array()),
                    'importFullText' => array('Import FullText', 'fnbr20/utils/import/formImportFullText', 'fa fa-upload fa16px', '', A_ACCESS, array()),
                    'exportFrames' => array('Export Frames', 'fnbr20/utils/export/formExportFrames', 'fa fa-download fa16px', '', A_ACCESS, array()),
                    'importFrames' => array('Import Frames', 'fnbr20/utils/import/formImportFrames', 'fa fa-upload fa16px', '', A_ACCESS, array()),
                    'exportCxnFS' => array('Export Cxn as FS', 'fnbr20/utils/export/formExportCxnFS', 'fa fa-download fa16px', '', A_ACCESS, array()),
                )),
            'messages' => array('Messages', 'fnbr20/auth/message/main', 'fa fa-envelope-o fa16px', '', A_ACCESS, array(
                    'mailmsg' => array('Mail Message', 'fnbr20/auth/message/formMail', 'fa fa-envelope-o fa16px', '', A_ACCESS, array()),
                )),
            'language' => array('Language', 'fnbr20/main/language', 'fa fa-flag-checkered fa16px', '', A_ACCESS, array(
                    'pt' => array('Portuguese', 'fnbr20/main/changeLanguage/pt', 'fnbrFlagPt', '', A_ACCESS, array()),
                    'en' => array('English', 'fnbr20/main/changeLanguage/en', 'fnbrFlagEn', '', A_ACCESS, array()),
                    'es' => array('Spanish', 'fnbr20/main/changeLanguage/es', 'fnbrFlagEs', '', A_ACCESS, array()),
                    'fr' => array('French', 'fnbr20/main/changeLanguage/es', 'fnbrFlagFr', '', A_ACCESS, array()),
                )),
            'profile' => array('Profile', 'fnbr20/main/profile', 'fa fa-info fa16px', '', A_ACCESS, array(
                    'myprofile' => array('My Profile', 'fnbr20/profile/formMyProfile', 'fa fa-credit-card fa16px', '', A_ACCESS, array()),
                    'changePass' => array('Change Password', 'fnbr20/profile/formChangePassword', 'fa fa-lock fa16px', '', A_ACCESS, array()),
                    'logout' => array('Logout', 'fnbr20/auth/login/logout', 'fa fa-sign-out fa16px', '', A_ACCESS, array()),
                )),
        ))
);
