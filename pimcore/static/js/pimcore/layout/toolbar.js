/**
 * Pimcore
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.pimcore.org/license
 *
 * @copyright  Copyright (c) 2009-2010 elements.at New Media Solutions GmbH (http://www.elements.at)
 * @license    http://www.pimcore.org/license     New BSD License
 */

pimcore.registerNS("pimcore.layout.toolbar");
pimcore.layout.toolbar = Class.create({

    initialize: function() {

        var user = pimcore.globalmanager.get("user");
        this.toolbar = Ext.getCmp("pimcore_panel_toolbar");

        var fileItems = [];

        fileItems.push({
            text: t("welcome"),
            iconCls: "pimcore_icon_welcome",
            handler: function () {
                try {
                    pimcore.globalmanager.get("layout_portal").activate();
                }
                catch (e) {
                    pimcore.globalmanager.add("layout_portal", new pimcore.layout.portal());
                }
            }
        });

        if (user.isAllowed("documents")) {
            fileItems.push({
                text: t("open_document_by_url"),
                iconCls: "pimcore_icon_open_document_by_url",
                handler: pimcore.helpers.openDocumentByPathDialog
            });

            fileItems.push({
                text: t("open_document_by_id"),
                iconCls: "pimcore_icon_open_document_by_id",
                handler: pimcore.helpers.openElementByIdDialog.bind(this, "document")
            });
        }

        if (user.isAllowed("assets")) {
            fileItems.push({
                text: t("open_asset_by_id"),
                iconCls: "pimcore_icon_open_asset_by_id",
                handler: pimcore.helpers.openElementByIdDialog.bind(this, "asset")
            });
        }

        if (user.isAllowed("objects")) {
            fileItems.push({
                text: t("open_object_by_id"),
                iconCls: "pimcore_icon_open_object_by_id",
                handler: pimcore.helpers.openElementByIdDialog.bind(this, "object")
            });
        }


        fileItems.push({
            text: t("close_all_tabs"),
            iconCls: "pimcore_icon_menu_close_tabs",
            handler: this.closeAllTabs
        });


        this.fileMenu = new Ext.menu.Menu({
            items: fileItems
        });


        var extrasItems = [];


        if (user.isAllowed("glossary")) {
            extrasItems.push({
                text: t("glossary"),
                iconCls: "pimcore_icon_glossary",
                handler: this.editGlossary
            });
        }

        if (user.isAllowed("redirects")) {
            extrasItems.push({
                text: t("redirects"),
                iconCls: "pimcore_icon_redirects",
                handler: this.editRedirects
            });
        }

        if (user.isAllowed("translations")) {
            extrasItems.push({
                text: t("translations"),
                iconCls: "pimcore_icon_translations",
                handler: this.editTranslations
            });
        }

        if (user.isAllowed("recyclebin")) {
            extrasItems.push({
                text: t("recyclebin"),
                iconCls: "pimcore_icon_recyclebin",
                handler: this.recyclebin
            });
        }


        var seoMenu = [];

        if(user.isAllowed("documents") && user.isAllowed("seo_document_editor")) {
            seoMenu.push({
                text: t("seo_document_editor"),
                iconCls: "pimcore_icon_seo_document",
                handler: this.showDocumentSeo
            });
        }

        if(user.isAllowed("robots.txt")) {
            seoMenu.push({
                text: "robots.txt",
                iconCls: "pimcore_icon_robots",
                handler: this.showRobotsTxt
            });
        }

        if(user.isAllowed("http_errors")) {
            seoMenu.push({
                text: t("http_errors"),
                iconCls: "pimcore_icon_httperrorlog",
                handler: this.showHttpErrorLog
            });
        }

        if(user.isAllowed("reports")) {
            seoMenu.push({
                text: t("reports"),
                iconCls: "pimcore_icon_reports",
                handler: this.showReports.bind(this, null)
            });
        }

        if (seoMenu.length > 0) {
            extrasItems.push({
                text: t("search_engine_optimization"),
                iconCls: "pimcore_icon_seo",
                hideOnClick: false,
                menu: seoMenu
            });
        }

        if (user.isAllowed("plugins")) {
            extrasItems.push({
                text: t("extensions"),
                iconCls: "pimcore_icon_extensionmanager",
                hideOnClick: false,
                menu: [{
                    text: t("manage_extensions"),
                    iconCls: "pimcore_icon_extensionmanager_admin",
                    handler: this.extensionAdmin
                },{
                    text: t("download_extension"),
                    iconCls: "pimcore_icon_extensionmanager_download",
                    handler: this.extensionDownload
                },{
                    text: t("share_extension"),
                    iconCls: "pimcore_icon_extensionmanager_share",
                    handler: this.extensionShare
                }]
            });
        }

        var reportsMenu = [];

        if (user.isAllowed("reports")) {
            reportsMenu.push({
                text: t("reports"),
                iconCls: "pimcore_icon_reports",
                handler: this.showReports
            });
        }

        if (user.isAllowed("tag_snippet_management")) {
            reportsMenu.push({
                text: t("tag_snippet_management"),
                iconCls: "pimcore_icon_tag",
                handler: this.showTagManagement
            });
        }

        if (user.isAllowed("qr_codes")) {
            reportsMenu.push({
                text: t("qr_codes"),
                iconCls: "pimcore_icon_qrcode",
                handler: this.showQRCode
            });
        }

        if (user.isAllowed("targeting")) {
            reportsMenu.push({
                text: t("targeting"),
                iconCls: "pimcore_icon_tab_targeting",
                handler: this.showTargeting,
                hidden: !pimcore.settings.targeting_enabled
            });
        }

        if (user.isAllowed("newsletter")) {
            reportsMenu.push({
                text: t("newsletter"),
                iconCls: "pimcore_icon_newsletter",
                handler: this.showNewsletter
            });
        }

        if (reportsMenu.length > 0) {
            extrasItems.push({
                text: t("reports_and_marketing"),
                iconCls: "pimcore_icon_reports",
                id: "pimcore_mainmenu_extras_reports",
                hideOnClick: false,
                menu: reportsMenu
            });
        }

        if (user.isAllowed("notes_events")) {
            extrasItems.push({
                text: t('notes_events'),
                iconCls: "pimcore_icon_tab_notes",
                handler: this.notes
            });
        }

        extrasItems.push("-");

        if (user.isAllowed("backup")) {
            extrasItems.push({
                text: t("backup"),
                iconCls: "pimcore_icon_backup",
                handler: this.backup
            });
        }

        if (user.isAllowed("bounce_mail_inbox")) {
            extrasItems.push({
                text: t("bounce_mail_inbox"),
                iconCls: "pimcore_icon_bouncemail",
                handler: this.showBounceMailInbox
            });
        }

        if (user.admin) {
            extrasItems.push({
                text: t("update"),
                iconCls: "pimcore_icon_update",
                handler: function () {
                    var update = new pimcore.settings.update();
                }
            });

            extrasItems.push({
                text: t("language_download"),
                iconCls: "pimcore_icon_languages",
                handler: function () {
                    var update = new pimcore.settings.languages();
                }
            });

            extrasItems.push({
                text: t("maintenance_mode"),
                iconCls: "pimcore_icon_maintenance",
                handler: this.showMaintenance
            });

            extrasItems.push({
                text: t("systemlog"),
                iconCls: "pimcore_icon_systemlog",
                handler: this.showLog
            });

            extrasItems.push({
                text: t("system_infos_and_tools"),
                iconCls: "pimcore_icon_info",
                hideOnClick: false,
                menu: [{
                    text: "PHP Info",
                    iconCls: "pimcore_icon_php",
                    handler: this.showPhpInfo
                },{
                    text: "System-Requirements Check",
                    iconCls: "pimcore_icon_systemrequirements",
                    handler: this.showSystemRequirementsCheck
                },{
                    text: "Server Info",
                    iconCls: "pimcore_icon_server_info",
                    handler: this.showServerInfo
                },{
                    text: "Database Administration",
                    iconCls: "pimcore_icon_mysql",
                    handler: this.showAdminer
                },{
                    text: t("server_fileexplorer"),
                    iconCls: "pimcore_icon_fileexplorer",
                    handler: this.showFilexplorer
                }]
            });
        }


        if (extrasItems.length > 0) {
            this.extrasMenu = new Ext.menu.Menu({
                items: extrasItems
            });
        }

        // settings menu
        var settingsItems = [];

        if (user.isAllowed("document_types")) {
            settingsItems.push({
                text: t("document_types"),
                iconCls: "pimcore_icon_doctypes",
                handler: this.editDocumentTypes
            });
        }
        if (user.isAllowed("predefined_properties")) {
            settingsItems.push({
                text: t("predefined_properties"),
                iconCls: "pimcore_icon_properties",
                handler: this.editProperties
            });
        }

        if (user.isAllowed("system_settings")) {
            settingsItems.push({
                text: t("system"),
                iconCls: "pimcore_icon_system",
                handler: this.systemSettings
            });
        }
        
        if (user.isAllowed("website_settings")) {
            settingsItems.push({
                text: t("website"),
                iconCls: "pimcore_icon_website",
                handler: this.websiteSettings
            });
        }

        if (user.admin) {
            settingsItems.push({
                text: t("users") + " / " + t("roles"),
                iconCls: "pimcore_icon_users",
                hideOnClick: false,
                menu: [{
                    text: t("users"),
                    handler: this.editUsers,
                    iconCls: "pimcore_icon_users"
                }, {
                    text: t("roles"),
                    handler: this.editRoles,
                    iconCls: "pimcore_icon_roles"
                }]
            });
        } else {
            settingsItems.push({
                text: t("profile"),
                iconCls: "pimcore_icon_users",
                handler: this.editProfile
            });
        }

        if (user.isAllowed("thumbnails")) {
            settingsItems.push({
                text: t("thumbnails"),
                iconCls: "pimcore_icon_thumbnails",
                hideOnClick: false,
                menu : [{
                    text: t("image_thumbnails"),
                    iconCls: "pimcore_icon_thumbnails",
                    handler: this.editThumbnails
                },{
                    text: t("video_thumbnails"),
                    iconCls: "pimcore_icon_videothumbnails",
                    handler: this.editVideoThumbnails
                }]
            });
        }

        if (user.isAllowed("objects")) {

            var objectMenu = {
                text: t("object"),
                iconCls: "pimcore_icon_object",
                hideOnClick: false,
                menu: []
            };

            if (user.isAllowed("classes")) {
                objectMenu.menu.push({
                    text: t("classes"),
                    iconCls: "pimcore_icon_classes",
                    handler: this.editClasses
                });
                
                objectMenu.menu.push({
                    text: t("field_collections"),
                    iconCls: "pimcore_icon_fieldcollections",
                    handler: this.editFieldcollections
                });

                objectMenu.menu.push({
                    text: t("objectbricks"),
                    iconCls: "pimcore_icon_objectbricks",
                    handler: this.editObjectBricks
                });

                objectMenu.menu.push({
                    text: t("keyvalue_menu_config"),
                    iconCls: "pimcore_icon_key",
                    handler: this.keyValueSettings
                });

                objectMenu.menu.push({
                    text: t("custom_views"),
                    iconCls: "pimcore_icon_custom_views",
                    handler: this.editCustomViews
                });

                settingsItems.push(objectMenu);
            }
        }

        if (user.isAllowed("routes")) {
            settingsItems.push({
                text: t("static_routes"),
                iconCls: "pimcore_icon_routes",
                handler: this.editRoutes
            });
        }

        if (user.isAllowed("clear_cache") || user.isAllowed("clear_temp_files")) {

            var cacheMenu = {
                text: t("cache"),
                iconCls: "pimcore_icon_menu_clear_cache",
                hideOnClick: false,
                menu: []
            };

            if (user.isAllowed("clear_cache")) {
                cacheMenu.menu.push({
                    text: t("clear_only_output_cache"),
                    iconCls: "pimcore_icon_menu_clear_cache",
                    handler: this.clearOutputCache
                });
            }

            if (user.isAllowed("clear_cache")) {
                cacheMenu.menu.push({
                    text: t("clear_cache"),
                    iconCls: "pimcore_icon_menu_clear_cache",
                    handler: this.clearCache
                });
            }

            if (user.isAllowed("clear_temp_files")) {
                cacheMenu.menu.push({
                    text: t("clear_temporary_files"),
                    iconCls: "pimcore_icon_menu_clear_cache",
                    handler: this.clearTemporaryFiles
                });
            }

            if(pimcore.settings.document_generatepreviews) {
                cacheMenu.menu.push({
                    text: t("generate_page_previews"),
                    iconCls: "pimcore_icon_page",
                    handler: this.generatePagePreviews
                });
            }

            settingsItems.push(cacheMenu);
        }

        // admin translations only for admins
        if(user.admin) {
            settingsItems.push({
                text: t("translations_admin"),
                iconCls: "pimcore_icon_translations",
                handler: this.editTranslationsSpecific
            });
        }

        if (user.isAllowed("reports") && user.isAllowed("system_settings")) {
            settingsItems.push({
                text: t("reports_and_marketing") + " (beta)",
                iconCls: "pimcore_icon_reports",
                handler: this.reportSettings
            });
        }

        // help menu
        if (settingsItems.length > 0) {
            this.settingsMenu = new Ext.menu.Menu({
                items: settingsItems
            });
        }

        this.helpMenu = new Ext.menu.Menu({
            items: [
                {
                    text: t("documentation"),
                    iconCls: "pimcore_icon_menu_documentation",
                    handler: function () {
                        window.open("http://www.pimcore.org/wiki/");
                    }
                },
                {
                    text: t("report_bugs"),
                    iconCls: "pimcore_icon_menu_bugs",
                    handler: function () {
                        window.open("http://www.pimcore.org/issues");
                    }
                },
                {
                    text: t("about"),
                    iconCls: "pimcore_icon_menu_about",
                    handler: function () {
                        window.open("http://www.pimcore.org/");
                    }
                }
            ]
        });


        this.toolbar.add({
            text: t('file'),
            iconCls: "pimcore_icon_menu_file",
            cls: "pimcore_main_menu",
            menu: this.fileMenu
        });


        if (this.extrasMenu) {
            this.toolbar.add({
                text: t('extras'),
                iconCls: "pimcore_icon_menu_extras",
                cls: "pimcore_main_menu",
                menu: this.extrasMenu
            });
        }


        if (this.settingsMenu) {
            this.toolbar.add({
                text: t('settings'),
                iconCls: "pimcore_icon_menu_settings",
                cls: "pimcore_main_menu",
                menu: this.settingsMenu
            });
        }
        
        this.toolbar.add({
            text: t('search'),
            iconCls: "pimcore_icon_menu_search",
            cls: "pimcore_main_menu",
            handler: function () {
                pimcore.helpers.itemselector(false, function (selection) {
                    pimcore.helpers.openElement(selection.id,selection.type, selection.subtype);
                }, null, {moveToTab: true} );
            }
        });
        
        this.toolbar.add("->");
        

        if (('webkitSpeechRecognition' in window)) {
            this.toolbar.add({
                iconCls: "",
                cls: "pimcore_main_menu",
                handler: function (btn) {
                    var speechRecognitionButton = btn;
                    if(btn.pressed) {

                        var win = new Ext.Window({
                            modal: true,
                            width: 200,
                            height: 100,
                            title: t("language"),
                            bodyStyle: "padding:10px",
                            items: [{
                                xtype: "combo",
                                itemId: "language",
                                store: [['af-ZA', "Afrikaans"],  ['id-ID', "Bahasa Indonesia"],
                                    ['ms-MY', "Bahasa Melayu"], ['ca-ES', "Català"], ['cs-CZ', "Čeština"],
                                    ['de-DE', "Deutsch"], ['en-AU', 'English (Australia)'],
                                    ['en-CA', 'English (Canada)'], ['en-IN', 'English (India)'],
                                    ['en-NZ', 'English (New Zealand)'], ['en-ZA', 'English (South Africa)'],
                                    ['en-GB', 'English (United Kingdom)'], ['en-US', 'English (United States)'],
                                    ['es-AR', 'Español (Argentina)'], ['es-BO', 'Español (Bolivia)'],
                                    ['es-CL', 'Español (Chile)'], ['es-CO', 'Español (Colombia)'],
                                    ['es-CR', 'Español (Costa Rica)'], ['es-EC', 'Español (Ecuador)'],
                                    ['es-SV', 'Español (El Salvador)'], ['es-ES', 'Español (España)'],
                                    ['es-US', 'Español (Estados Unidos)'], ['es-GT', 'Español (Guatemala)'],
                                    ['es-HN', 'Español (Honduras)'], ['es-MX', 'Español (México)'],
                                    ['es-NI', 'Español (Nicaragua)'], ['es-PA', 'Español (Panamá)'],
                                    ['es-PY', 'Español (Paraguay)'], ['es-PE', 'Español (Perú)'],
                                    ['es-PR', 'Español (Puerto Rico)'], ['es-DO', 'Español (República Dominicana)'],
                                    ['es-UY', 'Español (Uruguay)'], ['es-VE', 'Español (Venezuela)'],
                                    ['eu-ES', "Euskara"], ['fr-FR', "Français"], ['gl-ES', "Galego"],
                                    ['hr_HR', "Hrvatski"], ['zu-ZA', "IsiZulu"], ['is-IS', "Íslenska"],
                                    ['it-IT', 'Italiano (Italia)'], ['it-CH', 'Italiano (Svizzera)'],
                                    ['hu-HU', "Magyar"], ['nl-NL', "Nederlands"], ['nb-NO', "Norsk bokmål"],
                                    ['pl-PL', "Polski"], ['pt-BR', 'Português (Brasil)'],
                                    ['pt-PT', 'Português (Portugal)'], ['ro-RO', "Română"],
                                    ['sk-SK', "Slovenčina"], ['fi-FI', "Suomi"], ['sv-SE', "Svenska"],
                                    ['tr-TR', "Türkçe"], ['bg-BG', "български"], ['ru-RU', "Pусский"],
                                    ['sr-RS', "Српски"], ['ko-KR', "한국어"], ['cmn-Hans-CN', '中文 普通话 (中国大陆)'],
                                    ['cmn-Hans-HK', '中文 普通话 (香港)'], ['cmn-Hant-TW', '中文(台灣)'],
                                    ['yue-Hant-HK', '中文 粵語 (香港)'], ['ja-JP', "日本語"], ['la', "Lingua latīna"]],
                                typeAhead: false,
                                editable: false,
                                forceSelection: true,
                                triggerAction: "all"
                            }],
                            buttons: [{
                                xtype: "button",
                                text: t("apply"),
                                iconCls: "pimcore_icon_apply",
                                handler: function () {
                                    var lang = win.getComponent("language").getValue();

                                    if(!lang) {
                                        return;
                                    }

                                    win.close();


                                    var offset = speechRecognitionButton.getEl().getOffsetsTo(Ext.getBody());
                                    offset[0] = offset[0] - 260;

                                    var interimToolTip = new Ext.Tip({
                                        x: offset[0],
                                        y: offset[1],
                                        html: "",
                                        width: 250,
                                        autoHide: false,
                                        closable: false
                                    });

                                    var recognition = new webkitSpeechRecognition();
                                    recognition.continuous = true;
                                    recognition.interimResults = true;
                                    recognition.lang = lang;

                                    recognition.onresult = function (event) {
                                        var interim_transcript = '';
                                        var final_transcript = "";

                                        for (var i = event.resultIndex; i < event.results.length; ++i) {
                                            if (event.results[i].isFinal) {
                                                final_transcript += event.results[i][0].transcript;
                                            } else {
                                                interim_transcript += event.results[i][0].transcript;
                                            }
                                        }

                                        if(final_transcript) {
                                            interimToolTip.hide();
                                            pimcore.helpers.insertTextAtCursorPosition(final_transcript);
                                        } else {
                                            interimToolTip.show();
                                            interimToolTip.update(interim_transcript);
                                        }
                                    }

                                    recognition.onstart = function () { }
                                    recognition.onerror = function (event) {
                                        console.log("SpeechRecognition ERROR");
                                        console.log(event);

                                        if(speechRecognitionButton.pressed) {
                                            interimToolTip.hide();
                                            speechRecognitionButton.toggle();
                                        }
                                    };
                                    recognition.onend = function () {
                                        if(speechRecognitionButton.pressed) {
                                            interimToolTip.hide();
                                            speechRecognitionButton.toggle();
                                        }
                                    };

                                    recognition.start();

                                    pimcore.globalmanager.add("recognition", recognition);
                                }
                            }]
                        });

                        win.show();
                    } else {
                        if(pimcore.globalmanager.exists("recognition")) {
                            var recognition = pimcore.globalmanager.get("recognition");
                            recognition.stop();
                            pimcore.globalmanager.remove("recognition");
                        }
                    }
                },
                enableToggle: true
            });
        }

        if (user.isAllowed("seemode")) {
            this.toolbar.add({
                text: t("seemode"),
                iconCls: "pimcore_icon_menu_seemode",
                cls: "pimcore_main_menu",
                handler: pimcore.helpers.openSeemode
            });
        }

        this.toolbar.add({
            text: t('help'),
            iconCls: "pimcore_icon_menu_help",
            cls: "pimcore_main_menu",
            menu: this.helpMenu
        });

        this.toolbar.add({
            text: t('logout'),
            iconCls: "pimcore_icon_menu_logout",
            cls: "pimcore_main_menu",
            handler: this.logout
        });
        

        this.toolbar.add(new Ext.Toolbar.Spacer({
            width: "150"
        }));


        return;
    },


    closeAllTabs: function () {
        pimcore.helpers.closeAllElements();
    },

    editDocumentTypes: function () {

        try {
            pimcore.globalmanager.get("document_types").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("document_types", new pimcore.settings.document.doctypes());
        }
    },

    editProperties: function () {

        try {
            pimcore.globalmanager.get("predefined_properties").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("predefined_properties", new pimcore.settings.properties.predefined());
        }
    },

    backup: function () {
        var backup = new pimcore.settings.backup();
    },
    
    recyclebin: function () {
        try {
            pimcore.globalmanager.get("recyclebin").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("recyclebin", new pimcore.settings.recyclebin());
        }
    },

    editUsers: function () {

        try {
            pimcore.globalmanager.get("users").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("users", new pimcore.settings.user.panel());
        }
    },

    editRoles: function () {

        try {
            pimcore.globalmanager.get("roles").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("roles", new pimcore.settings.user.role.panel());
        }
    },

    editProfile: function () {

        try {
            pimcore.globalmanager.get("profile").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("profile", new pimcore.settings.profile.panel());
        }
    },

    editThumbnails: function () {
        try {
            pimcore.globalmanager.get("thumbnails").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("thumbnails", new pimcore.settings.thumbnail.panel());
        }
    },

    editVideoThumbnails: function () {
        try {
            pimcore.globalmanager.get("videothumbnails").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("videothumbnails", new pimcore.settings.videothumbnail.panel());
        }
    },

    editTranslations: function () {
        try {
            pimcore.globalmanager.get("translationwebsitemanager").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("translationwebsitemanager", new pimcore.settings.translation.website());
        }
    },

    editTranslationsSpecific: function () {
        try {
            pimcore.globalmanager.get("translationadminmanager").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("translationadminmanager", new pimcore.settings.translation.admin());
        }
    },

    editRoutes: function () {

        try {
            pimcore.globalmanager.get("staticroutes").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("staticroutes", new pimcore.settings.staticroutes());
        }
    },


    editRedirects: function () {

        try {
            pimcore.globalmanager.get("redirects").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("redirects", new pimcore.settings.redirects());
        }
    },

    showLog: function () {

        try {
            pimcore.globalmanager.get("systemlog").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("systemlog", new pimcore.settings.systemlog());
        }
    },

    generatePagePreviews: function ()  {
        Ext.Ajax.request({
            url: '/admin/page/get-list',
            success: function (res) {
                var data = Ext.decode(res.responseText);
                if(data && data.success) {
                    var items = data.data;
                    var totalItems = items.length;

                    var progressBar = new Ext.ProgressBar({
                        text: t('initializing')
                    });

                    var progressWin = new Ext.Window({
                        title: t("generate_page_previews"),
                        layout:'fit',
                        width:500,
                        bodyStyle: "padding: 10px;",
                        closable:false,
                        plain: true,
                        modal: false,
                        items: [progressBar]
                    });

                    progressWin.show();

                    var generate = function () {
                        if(items.length > 1) {
                            var next = items.shift();

                            var date = new Date();
                            var path = next.path + "?pimcore_preview=true&time=" + date.getTime();

                            pimcore.helpers.generatePagePreview(next.id, path, function () {
                                generate();
                            });

                            var status = (totalItems-items.length) / totalItems;
                            progressBar.updateProgress(status, (Math.ceil(status*100) + "%"));
                        } else {
                            progressWin.close();
                        }
                    };

                    generate();
                }
            }
        });
    },

    showBounceMailInbox: function () {

        try {
            pimcore.globalmanager.get("bouncemailinbox").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("bouncemailinbox", new pimcore.settings.bouncemailinbox());
        }
    },

    showReports: function (reportClass, reportConfig) {
        try {
            pimcore.globalmanager.get("reports").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("reports", new pimcore.report.panel());
        }

        // this is for generated/configured reports like the SQL Report
        try {
            if(reportClass) {
                pimcore.globalmanager.get("reports").openReport(reportClass, reportConfig);
            }
        } catch (e) {

        }
    },

    showTagManagement: function () {
        try {
            pimcore.globalmanager.get("tagmanagement").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("tagmanagement", new pimcore.settings.tagmanagement.panel());
        }
    },

    showQRCode: function () {
        try {
            pimcore.globalmanager.get("qrcode").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("qrcode", new pimcore.report.qrcode.panel());
        }
    },

    showNewsletter: function () {
        try {
            pimcore.globalmanager.get("newsletter").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("newsletter", new pimcore.report.newsletter.panel());
        }
    },

    showTargeting: function () {
        var tabPanel = Ext.getCmp("pimcore_panel_tabs");
        try {
            tabPanel.activate(pimcore.globalmanager.get("targeting").getLayout());
        }
        catch (e) {
            var targeting = new pimcore.settings.targeting.panel();
            pimcore.globalmanager.add("targeting", targeting);

            tabPanel.add(targeting.getLayout());
            tabPanel.activate(targeting.getLayout());

            targeting.getLayout().on("destroy", function () {
                pimcore.globalmanager.remove("targeting");
            }.bind(this));

            pimcore.layout.refresh();
        }
    },

    notes: function () {
        try {
            pimcore.globalmanager.get("notes").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("notes", new pimcore.element.notes());
        }
    },

    editGlossary: function () {

        try {
            pimcore.globalmanager.get("glossary").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("glossary", new pimcore.settings.glossary());
        }
    },

    systemSettings: function () {

        try {
            pimcore.globalmanager.get("settings_system").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("settings_system", new pimcore.settings.system());
        }
    },
    
    websiteSettings: function () {

        try {
            pimcore.globalmanager.get("settings_website").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("settings_website", new pimcore.settings.website());
        }
    },

    reportSettings: function () {

        try {
            pimcore.globalmanager.get("reports_settings").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("reports_settings", new pimcore.report.settings());
        }
    },

    keyValueSettings: function () {
        try {
            pimcore.globalmanager.get("keyvalue_config").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("keyvalue_config", new pimcore.object.keyvalue.configpanel());
        }
    },

    editClasses: function () {
        try {
            pimcore.globalmanager.get("classes").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("classes", new pimcore.object.klass());
        }
    },

    editFieldcollections: function () {
        try {
            pimcore.globalmanager.get("fieldcollections").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("fieldcollections", new pimcore.object.fieldcollection());
        }
    },

    editObjectBricks: function () {
        try {
            pimcore.globalmanager.get("objectbricks").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("objectbricks", new pimcore.object.objectbrick());
        }
    },

    editCustomViews: function () {
        try {
            pimcore.globalmanager.get("customviews").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("customviews", new pimcore.object.customviews.settings());
        }
    },

    showDocumentSeo: function () {
        try {
            pimcore.globalmanager.get("document_seopanel").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("document_seopanel", new pimcore.document.seopanel());
        }
    },

    showRobotsTxt: function () {
        try {
            pimcore.globalmanager.get("robotstxt").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("robotstxt", new pimcore.settings.robotstxt());
        }
    },

    showHttpErrorLog: function () {
        try {
            pimcore.globalmanager.get("http_error_log").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("http_error_log", new pimcore.settings.httpErrorLog());
        }
    },

    clearCache: function () {
        Ext.Msg.confirm(t('warning'), t('system_performance_stability_warning'), function(btn){
            if (btn == 'yes'){
                Ext.Ajax.request({
                    url: '/admin/settings/clear-cache'
                });
            }
        });
    },

    clearOutputCache: function () {
        Ext.Ajax.request({
            url: '/admin/settings/clear-output-cache'
        });
    },

    clearTemporaryFiles: function () {
        Ext.Msg.confirm(t('warning'), t('system_performance_stability_warning'), function(btn){
            if (btn == 'yes'){
                Ext.Ajax.request({
                    url: '/admin/settings/clear-temporary-files'
                });
            }
        });
    },

    logout: function () {
        location.href = "/admin/login/logout/";
    },

    showFilexplorer: function () {
        try {
            pimcore.globalmanager.get("fileexplorer").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("fileexplorer", new pimcore.settings.fileexplorer.explorer());
        }
    },

    showMaintenance: function () {
        new pimcore.settings.maintenance();
    },

    extensionShare: function () {
        try {
            pimcore.globalmanager.get("extensionmanager_share").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("extensionmanager_share", new pimcore.extensionmanager.share());
        }
    },

    extensionAdmin: function () {
        try {
            pimcore.globalmanager.get("extensionmanager_admin").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("extensionmanager_admin", new pimcore.extensionmanager.admin());
        }
    },

    extensionDownload: function () {
        try {
            pimcore.globalmanager.get("extensionmanager_download").activate();
        }
        catch (e) {
            pimcore.globalmanager.add("extensionmanager_download", new pimcore.extensionmanager.download());
        }
    },

    showPhpInfo: function () {

        var id = "phpinfo";

        try {
            pimcore.globalmanager.get(id).activate();
        }
        catch (e) {
            pimcore.globalmanager.add(id, new pimcore.tool.genericiframewindow(id, "/admin/misc/phpinfo",
                                                                "pimcore_icon_php", "PHP Info"));
        }

    },

    showServerInfo: function () {

        var id = "serverinfo";

        try {
            pimcore.globalmanager.get(id).activate();
        }
        catch (e) {
            pimcore.globalmanager.add(id, new pimcore.tool.genericiframewindow(id,
                            "/pimcore/modules/3rdparty/linfo/index.php", "pimcore_icon_server_info", "Server Info"));
        }

    },

    showSystemRequirementsCheck: function () {

        var id = "systemrequirementscheck";

        try {
            pimcore.globalmanager.get(id).activate();
        }
        catch (e) {
            pimcore.globalmanager.add(id, new pimcore.tool.genericiframewindow(id, "/install/check/",
                                                    "pimcore_icon_systemrequirements", "System-Requirements Check"));
        }

    },

    showAdminer: function () {

        var id = "adminer";

        try {
            pimcore.globalmanager.get(id).activate();
        }
        catch (e) {
            pimcore.globalmanager.add(id, new pimcore.tool.genericiframewindow(id,
                            "/pimcore/modules/3rdparty/adminer/index.php", "pimcore_icon_mysql", "Database Admin"));
        }

    }


});