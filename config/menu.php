<?php

/**
 * menu 列表 2020/08/05 11:37:37
 *
 * 活動暫時拿掉
 */
return [
    [
        'mainList' => 'home',
    ],
    [
        'mainList' => 'agent',
        'subList' => [
            'agent_agent' => 'S_AGENT_AGENT',
            'agent_sub' => 'S_AGENT_SUB',
            'agent_CS' => 'S_AGENT_CS',
            'agent_ActionLog' => 'S_AGENT_ACTION_LOG'
        ]
    ],
    [
        'mainList' => 'member',
        'subList' => [
            'member_member' => 'S_MEMBER_MEMBER',
            // 'member_detail' => 'S_MEMBER_DETAIL',
            'member_rank' => 'S_MEMBER_RANK',
            'member_TagArrangement' => 'S_MEMBER_TAG_ARRANGEMENT'
        ]
    ],
    [
        'mainList' => 'BusinessReport',
    ],
    [
        'mainList' => 'report',
        'subList' => [
            'report_GameReport' => 'S_REPORT_GAME_REPORT',
            'report_WalletReport' => 'S_REPORT_WALLET_REPORT',
            'report_TransactionReport' => 'S_REPORT_TRANSACTION_REPORT',
            'report_BetReport' => 'S_REPORT_BET_REPORT'
        ]
    ],
    [
        'mainList' => 'game',
        'tabList' => [
            'game_ApiCommissionPercentageSetting' => 'S_GAME_API_COMMISSION_PERCENTAGE_SETTING',
            'game_MaintenanceTimeSetting' => 'S_GAME_MAINTENANCE_TIME_SETTING'
        ]
    ],
    [
        'mainList' => 'CustomerService',
        'subList' => [
            'CustomerService_ComplaintLetter' => 'S_CUSTOMER_SERVICE_COMPLAINT_LETTER',
            'CustomerService_LineList' => 'S_CUSTOMER_SERVICE_LINE_LIST',
            'CustomerService_SmsList' => 'S_CUSTOMER_SERVICE_SMS_LIST'
        ]
    ],
    [
        'mainList' => 'promotion',
        'subList' => [
            'promotion_RankList' => 'S_PROMOTION_RANK_LIST',
            'promotion_library' => 'S_PROMOTION_LIBRARY'
        ]
    ],
    [
        'mainList' => 'event',
        'subList' => [
            'event_FrontConfig' => 'S_EVENT_FRONTCONFIG',
            'event_QualificationConfig' => 'S_EVENT_QUALIFICATIONCONFIG'
        ]
    ],
    [
        'mainList' => 'system',
        'subList' => [
            'system_permissions' => 'S_SYSTEM_PERMISSIONS',
            'system_VIP' => 'S_SYSTEM_VIP',
            'system_share' => 'S_SYSTEM_SHARE',
            'system_ThirdPartyFeeSetting' => 'S_SYSTEM_THIRD_PARTY_FEE_SETTING',
            'system_WageringRequirement' => 'S_SYSTEM_WAGERING_REQUIREMENT',
            'system_language' => 'S_SYSTEM_LANGUAGE',
            'system_maintenance' => 'S_SYSTEM_MAINTENANCE'
        ]
    ],
    [
        'mainList' => 'front',
        'subList' => [
            'front_banner' => 'S_FRONT_BANNER',
            'front_GameGroup' => 'S_FRONT_GAME_GROUP',
            'front_marquee' => 'S_FRONT_MARQUEE',
            'front_message' => 'S_FRONT_MESSAGE',
            'front_CharitableEvent' => 'S_FRONT_CHARITABLE_EVENT',
            'front_DepositMethod' => 'S_FRONT_DEPOSIT_METHOD',
            'front_AlternateURL' => 'S_FRONT_ALTERNATE_URL'
        ]
    ],
    [
        'mainList' => 'RiskManagement',
        'subList' => [
            'RiskManagement_AccountArchive' => 'S_RISK_MANAGEMENT_ACCOUNT_ARCHIVE',
            'RiskManagement_PlayerTracking' => 'S_RISK_MANAGEMENT_PLAYER_TRACKING',
            'RiskManagement_DuplicateIp' => 'S_RISK_MANAGEMENT_DUPLICATE_IP',
            'RiskManagement_ApiMaxLoss' => 'S_RISK_MANAGEMENT_API_MAX_LOSS'
        ]
    ],
    [
        'mainList' => 'PersonalSetting',
        // 'subList' => [
        //     'PersonalSetting_AccountInformation' => 'S_PERSONAL_SETTING_ACCOUNT_INFORMATION',
        //     'PersonalSetting_PointTransferRecord' => 'S_PERSONAL_SETTING_POINT_TRANSFER_RECORD',
        //     'PersonalSetting_QuotaRecord' => 'S_PERSONAL_SETTING_QUOTA_RECORD'
        // ]
    ],
    [
        'mainList' => 'FinancialManagement',
        'subList' => [
            'FinancialManagement_BankRequest' => 'S_FINANCIAL_MANAGEMENT_BANK_REQUEST',
            'FinancialManagement_WithdrawRequest' => 'S_FINANCIAL_MANAGEMENT_WITHDRAW_REQUEST',
            'FinancialManagement_WithdrawRecord' => 'S_FINANCIAL_MANAGEMENT_WITHDRAW_RECORD',
            'FinancialManagement_DepositRecord' => 'S_FINANCIAL_MANAGEMENT_DEPOSIT_RECORD'
        ],
        'tabList' => [
            'FinancialManagement_BankRequest' => [
                'FinancialManagement_BankRequest_WaitingRequest' => 'S_FINANCIAL_MANAGEMENT_BANK_REQUEST_WAITING_REQUEST',
                'FinancialManagement_BankRequest_RequestList' => 'S_FINANCIAL_MANAGEMENT_BANK_REQUEST_REQUEST_LIST',
            ],
            'FinancialManagement_WithdrawRecord' => [
                'FinancialManagement_WithdrawRecord_WaitingRequestList' => 'S_FINANCIAL_MANAGEMENT_WITHDRAW_RECORD_WAITING_REQUEST_LIST',
                'FinancialManagement_WithdrawRecord_PassedList' => 'S_FINANCIAL_MANAGEMENT_WITHDRAW_RECORD_PASSED_LIST',
                'FinancialManagement_WithdrawRecord_NotPassedList' => 'S_FINANCIAL_MANAGEMENT_WITHDRAW_RECORD_NOT_PASSED_LIST'
            ]
        ]
    ]
];
