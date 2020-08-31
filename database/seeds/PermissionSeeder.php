<?php

use App\Models\Permission;
use App\Models\PermissionLevel;

class PermissionSeeder extends BasePermissionSeeder
{
    /**
     * permission array
     *
     * @var array
     */
    const permission = [
        'home' => 'home',
        'agent_agent' => 'agent_agent',
        'agent_agent_search' => 'agent_agent_search',
        'agent_agent_add' => 'agent_agent_add',
        'agent_agent_below' => 'agent_agent_below',
        'agent_agent_up' => 'agent_agent_up',
        'agent_agent_edit' => 'agent_agent_edit',
        'agent_agent_data' => 'agent_agent_data',
        'agent_agent_subAccount' => 'agent_agent_subAccount',
        'agent_agent_member' => 'agent_agent_member',
        'agent_agent_log' => 'agent_agent_log',
        'agent_sub' => 'agent_sub',
        'agent_sub_add' => 'agent_sub_add',
        'agent_sub_edit' => 'agent_sub_edit',
        'agent_sub_details' => 'agent_sub_details',
        'agent_CS' => 'agent_CS',
        'agent_CS_add' => 'agent_CS_add',
        'agent_CS_log' => 'agent_CS_log',
        'agent_CS_edit' => 'agent_CS_edit',
        'agent_CS_details' => 'agent_CS_details',
        'agent_ActionLog' => 'agent_ActionLog',
        'member_member' => 'member_member',
        'member_member_search' => 'member_member_search',
        'member_member_BatchAdjustment' => 'member_member_BatchAdjustment',
        'member_member_add' => 'member_member_add',
        'member_member_AddTag' => 'member_member_AddTag',
        'member_member_status' => 'member_member_status',
        'member_member_WalletAdjustment_add' => 'member_member_WalletAdjustment_add',
        'member_member_WalletAdjustment_reduce' => 'member_member_WalletAdjustment_reduce',
        'member_detail' => 'member_detail',
        'member_detail_search' => 'member_detail_search',
        'member_detail_PasswordConfig' => 'member_detail_PasswordConfig',
        'member_detail_StatusConfig' => 'member_detail_StatusConfig',
        'member_detail_VipConfig' => 'member_detail_VipConfig',
        'member_detail_GameConfig' => 'member_detail_GameConfig',
        'member_detail_PhoneConfig' => 'member_detail_PhoneConfig',
        'member_detail_LineConfig' => 'member_detail_LineConfig',
        'member_detail_AddressConfig' => 'member_detail_AddressConfig',
        'member_detail_BankAccountConfig' => 'member_detail_BankAccountConfig',
        'member_rank' => 'member_rank',
        'member_TagArrangement' => 'member_TagArrangement',
        'member_TagArrangement_add' => 'member_TagArrangement_add',
        'member_TagArrangement_edit' => 'member_TagArrangement_edit',
        'member_TagArrangement_remove' => 'member_TagArrangement_remove',
        'BusinessReport_report' => 'BusinessReport_report',
        'BusinessReport_report_filter' => 'BusinessReport_report_filter',
        'BusinessReport_report_export' => 'BusinessReport_report_export',
        'BusinessReport_AgentReport_unfold' => 'BusinessReport_AgentReport_unfold',
        'BusinessReport_AgentReport_account' => 'BusinessReport_AgentReport_account',
        'BusinessReport_MemberReport_unfold' => 'BusinessReport_MemberReport_unfold',
        'BusinessReport_MemberReport_account' => 'BusinessReport_MemberReport_account',
        'BusinessReport_report_detail' => 'BusinessReport_report_detail',
        'report_GameReport' => 'report_GameReport',
        'report_WalletReport' => 'report_WalletReport',
        'report_TransactionReport' => 'report_TransactionReport',
        'report_BetReport' => 'report_BetReport',
        'game_ApiCommissionPercentageSetting' => 'game_ApiCommissionPercentageSetting',
        'game_ApiCommissionPercentageSettingFeature' => 'game_ApiCommissionPercentageSettingFeature',
        'game_MaintenanceTimeSetting' => 'game_MaintenanceTimeSetting',
        'game_MaintenanceTimeSettingFeature' => 'game_MaintenanceTimeSettingFeature',
        'CustomerService_ComplaintLetter' => 'CustomerService_ComplaintLetter',
        'CustomerService_LineList' => 'CustomerService_LineList',
        'CustomerService_LineConfig' => 'CustomerService_LineConfig',
        'CustomerService_SmsList' => 'CustomerService_SmsList',
        'CustomerService_SmsConfig' => 'CustomerService_SmsConfig',
        'promotion_RankList' => 'promotion_RankList',
        'promotion_library' => 'promotion_library',
        'promotion_library_upload' => 'promotion_library_upload',
        'promotion_library_download' => 'promotion_library_download',
        'promotion_library_remove' => 'promotion_library_remove',
        'event_FrontConfig' => 'event_FrontConfig',
        'event_FrontConfig_add' => 'event_FrontConfig_add',
        'event_FrontConfig_edit' => 'event_FrontConfig_edit',
        'event_FrontConfig_remove' => 'event_FrontConfig_remove',
        'event_FrontConfig_move' => 'event_FrontConfig_move',
        'event_QualificationConfig' => 'event_QualificationConfig',
        'event_QualificationConfig_Search' => 'event_QualificationConfig_Search',
        'event_QualificationConfig_BonusConfig' => 'event_QualificationConfig_BonusConfig',
        'event_QualificationConfig_BonusConfig_Confirm' => 'event_QualificationConfig_BonusConfig_Confirm',
        'system_permissions' => 'system_permissions',
        'system_permissions_add' => 'system_permissions_add',
        'system_permissions_see' => 'system_permissions_see',
        'system_VIP' => 'system_VIP',
        'system_VIP_add' => 'system_VIP_add',
        'system_VIP_edit' => 'system_VIP_edit',
        'system_share' => 'system_share',
        'system_share_add' => 'system_share_add',
        'system_share_edit' => 'system_share_edit',
        'system_ThirdPartyFeeSetting' => 'system_ThirdPartyFeeSetting',
        'system_ThirdPartyFeeSetting_display' => 'system_ThirdPartyFeeSetting_display',
        'system_ThirdPartyFeeSetting_save' => 'system_ThirdPartyFeeSetting_save',
        'system_WageringRequirement' => 'system_WageringRequirement',
        'system_WageringRequirement_save' => 'system_WageringRequirement_save',
        'system_language' => 'system_language',
        'system_language_front_upload' => 'system_language_front_upload',
        'system_language_front_add' => 'system_language_front_add',
        'system_language_front_edit' => 'system_language_front_edit',
        'system_language_front_remove' => 'system_language_front_remove',
        'system_language_backend_add' => 'system_language_backend_add',
        'system_language_backend_edit' => 'system_language_backend_edit',
        'system_language_backend_remove' => 'system_language_backend_remove',
        'system_maintenance' => 'system_maintenance',
        'system_maintenance_front_figure' => 'system_maintenance_front_figure',
        'system_maintenance_front_QRcode' => 'system_maintenance_front_QRcode',
        'system_maintenance_front_addtime' => 'system_maintenance_front_addtime',
        'system_maintenance_front_save' => 'system_maintenance_front_save',
        'system_maintenance_backend_figure' => 'system_maintenance_backend_figure',
        'system_maintenance_backend_addtime' => 'system_maintenance_backend_addtime',
        'system_maintenance_backend_save' => 'system_maintenance_backend_save',
        'front_banner' => 'front_banner',
        'front_banner_add' => 'front_banner_add',
        'front_banner_edit' => 'front_banner_edit',
        'front_banner_remove' => 'front_banner_remove',
        'front_GameGroup' => 'front_GameGroup',
        'front_GameGroup_confirm' => 'front_GameGroup_confirm',
        'front_marquee' => 'front_marquee',
        'front_marquee_add' => 'front_marquee_add',
        'front_marquee_edit' => 'front_marquee_edit',
        'front_marquee_remove' => 'front_marquee_remove',
        'front_message' => 'front_message',
        'front_message_add' => 'front_message_add',
        'front_message_content' => 'front_message_content',
        'front_message_receiver' => 'front_message_receiver',
        'front_message_edit' => 'front_message_edit',
        'front_CharitableEvent' => 'front_CharitableEvent',
        'front_CharitableEvent_add' => 'front_CharitableEvent_add',
        'front_CharitableEvent_remove' => 'front_CharitableEvent_remove',
        'front_CharitableEvent_save' => 'front_CharitableEvent_save',
        'front_DepositMethod' => 'front_DepositMethod',
        'front_DepositMethod_cvs' => 'front_DepositMethod_cvs',
        'front_DepositMethod_VirtualAccount(ATM)' => 'front_DepositMethod_VirtualAccount(ATM)',
        'front_DepositMethod_CreditCard' => 'front_DepositMethod_CreditCard',
        'front_DepositMethod_BankTransfer' => 'front_DepositMethod_BankTransfer',
        'front_DepositMethod_BankTransfer_add' => 'front_DepositMethod_BankTransfer_add',
        'front_DepositMethod_BankTransfer_save' => 'front_DepositMethod_BankTransfer_save',
        'front_DepositMethod_BankTransfer_remove' => 'front_DepositMethod_BankTransfer_remove',
        'front_AlternateURL' => 'front_AlternateURL',
        'front_AlternateURL_save' => 'front_AlternateURL_save',
        'front_AlternateURL_add' => 'front_AlternateURL_add',
        'RiskManagement_AccountArchive' => 'RiskManagement_AccountArchive',
        'RiskManagement_AccountArchive_modify' => 'RiskManagement_AccountArchive_modify',
        'RiskManagement_AccountArchive_RemovePhone' => 'RiskManagement_AccountArchive_RemovePhone',
        'RiskManagement_PlayerTracking' => 'RiskManagement_PlayerTracking',
        'RiskManagement_PlayerTracking_add' => 'RiskManagement_PlayerTracking_add',
        'RiskManagement_DuplicateIp' => 'RiskManagement_DuplicateIp',
        'RiskManagement_DuplicateIp_DuplicateLoginTimes' => 'RiskManagement_DuplicateIp_DuplicateLoginTimes',
        'RiskManagement_DuplicateIp_RemarksEdit' => 'RiskManagement_DuplicateIp_RemarksEdit',
        'RiskManagement_ApiMaxLoss' => 'RiskManagement_ApiMaxLoss',
        'RiskManagement_ApiMaxLoss_edit' => 'RiskManagement_ApiMaxLoss_edit',
        'RiskManagement_ApiMaxLoss_save' => 'RiskManagement_ApiMaxLoss_save',
        'PersonalSetting_AccountInformation' => 'PersonalSetting_AccountInformation',
        'PersonalSetting_AccountInformation_EditPassword' => 'PersonalSetting_AccountInformation_EditPassword',
        'PersonalSetting_AccountInformation_Alias' => 'PersonalSetting_AccountInformation_Alias',
        'PersonalSetting_AccountInformation_Layer' => 'PersonalSetting_AccountInformation_Layer',
        'PersonalSetting_AccountInformation_LoginRecord_other' => 'PersonalSetting_AccountInformation_LoginRecord_other',
        'PersonalSetting_AccountInformation_OperationRecord_other' => 'PersonalSetting_AccountInformation_OperationRecord_other',
        'PersonalSetting_AccountInformation_SelectPhoto' => 'PersonalSetting_AccountInformation_SelectPhoto',
        'PersonalSetting_AccountInformation_save' => 'PersonalSetting_AccountInformation_save',
        'PersonalSetting_PointTransferRecord' => 'PersonalSetting_PointTransferRecord',
        'PersonalSetting_QuotaRecord' => 'PersonalSetting_QuotaRecord',
        'FinancialManagement_BankRequest_WaitingRequest' => 'FinancialManagement_BankRequest_WaitingRequest',
        'FinancialManagement_BankRequest_WaitingRequest_EditStatus' => 'FinancialManagement_BankRequest_WaitingRequest_EditStatus',
        'FinancialManagement_BankRequest_WaitingRequest_Pass' => 'FinancialManagement_BankRequest_WaitingRequest_Pass',
        'FinancialManagement_BankRequest_WaitingRequest_NoPass' => 'FinancialManagement_BankRequest_WaitingRequest_NoPass',
        'FinancialManagement_BankRequest_RequestList' => 'FinancialManagement_BankRequest_RequestList',
        'FinancialManagement_BankRequest_RequestList_CheckRemark' => 'FinancialManagement_BankRequest_RequestList_CheckRemark',
        'FinancialManagement_WithdrawRequest' => 'FinancialManagement_WithdrawRequest',
        'FinancialManagement_WithdrawRequest_EditStatus' => 'FinancialManagement_WithdrawRequest_EditStatus',
        'FinancialManagement_WithdrawRequest_Pass' => 'FinancialManagement_WithdrawRequest_Pass',
        'FinancialManagement_WithdrawRequest_NoPass' => 'FinancialManagement_WithdrawRequest_NoPass',
        'FinancialManagement_WithdrawRecord_WaitingRequestList' => 'FinancialManagement_WithdrawRecord_WaitingRequestList',
        'FinancialManagement_WithdrawRecord_PassedList' => 'FinancialManagement_WithdrawRecord_PassedList',
        'FinancialManagement_WithdrawRecord_NotPassedList' => 'FinancialManagement_WithdrawRecord_NotPassedList',
        'FinancialManagement_WithdrawRecord_MemberDetail' => 'FinancialManagement_WithdrawRecord_MemberDetail',
        'FinancialManagement_DepositRecord' => 'FinancialManagement_DepositRecord',
        'FinancialManagement_DepositRecord_MemberDetail' => 'FinancialManagement_DepositRecord_MemberDetail',
    ];

    /**
     * owner except permission
     *
     * @var array
     */
    const ownerExceptPermission = [
        'CustomerService_LineConfig',
        'CustomerService_SmsConfig',
        'FinancialManagement_BankRequest_WaitingRequest_EditStatus',
        'FinancialManagement_BankRequest_WaitingRequest_Pass',
        'FinancialManagement_BankRequest_WaitingRequest_NoPass',
        'FinancialManagement_WithdrawRequest_EditStatus',
        'FinancialManagement_WithdrawRequest_Pass',
        'FinancialManagement_WithdrawRequest_NoPass'
    ];

    /**
     * customer-service-supervisor except permission
     *
     * @var array
     */
    const customerServiceSupervisorExceptPermission = [
        'home',
        'agent_agent_subAccount',
        'agent_sub',
        'agent_sub_add',
        'agent_sub_edit',
        'agent_sub_details',
        'BusinessReport_report',
        'BusinessReport_report_filter',
        'BusinessReport_report_export',
        'BusinessReport_AgentReport_unfold',
        'BusinessReport_AgentReport_account',
        'BusinessReport_MemberReport_unfold',
        'BusinessReport_MemberReport_account',
        'BusinessReport_report_detail',
        'report_GameReport',
        'report_TransactionReport',
        'game_ApiCommissionPercentageSetting',
        'promotion_RankList',
        'system_permissions',
        'system_permissions_add',
        'system_permissions_see',
        'system_VIP_add',
        'system_VIP_edit',
        'system_share_add',
        'system_share_edit',
        'system_ThirdPartyFeeSetting',
        'system_ThirdPartyFeeSetting_display',
        'system_ThirdPartyFeeSetting_save',
        'system_WageringRequirement_save',
        'system_language_front_add',
        'system_language_front_edit',
        'system_language_front_remove',
        'system_language_backend_add',
        'system_language_backend_edit',
        'system_language_backend_remove',
        'system_maintenance_backend_figure',
        'system_maintenance_backend_addtime',
        'system_maintenance_backend_save',
        'front_CharitableEvent',
        'front_CharitableEvent_add',
        'front_CharitableEvent_remove',
        'front_CharitableEvent_save',
    ];

    /**
     * customer service except few permission
     *
     * @var array
     */
    const customerServiceExceptFewPermission = [
        'agent_agent_add',
        'agent_agent_edit',
        'agent_agent_data',
        'agent_CS',
        'agent_CS_add',
        'agent_CS_log',
        'agent_CS_edit',
        'agent_CS_details',
        'member_detail_VipConfig',
        'member_detail_GameConfig',
        'member_detail_PhoneConfig',
        'member_detail_LineConfig',
        'member_detail_AddressConfig',
        'member_detail_BankAccountConfig',
        'member_TagArrangement_remove',
        'game_MaintenanceTimeSettingFeature',
        'CustomerService_LineList',
        'CustomerService_LineConfig',
        'event_FrontConfig',
        'event_FrontConfig_add',
        'event_FrontConfig_edit',
        'event_FrontConfig_remove',
        'event_FrontConfig_move',
        'system_permissions',
        'system_permissions_add',
        'system_permissions_see',
        'system_maintenance_front_figure',
        'system_maintenance_front_QRcode',
        'system_maintenance_front_addtime',
        'system_maintenance_front_save',
        'front_GameGroup_confirm',
        'front_DepositMethod_cvs',
        'front_DepositMethod_VirtualAccount(ATM)',
        'front_DepositMethod_CreditCard',
        'front_DepositMethod_BankTransfer',
        'front_DepositMethod_BankTransfer_add',
        'front_DepositMethod_BankTransfer_save',
        'front_DepositMethod_BankTransfer_remove',
        'front_AlternateURL_save',
        'front_AlternateURL_add',
        'RiskManagement_AccountArchive_modify',
        'RiskManagement_AccountArchive_RemovePhone',
        'RiskManagement_ApiMaxLoss_edit',
        'RiskManagement_ApiMaxLoss_save'
    ];

    /**
     * agent has permission
     *
     * @var array
     */
    const agentHasPermission = [
        'home',
        'agent_agent',
        'agent_agent_search',
        'agent_agent_below',
        'agent_agent_up',
        'agent_agent_log',
        'agent_sub',
        'agent_sub_add',
        'agent_sub_edit',
        'agent_sub_details',
        'agent_ActionLog',
        'member_member',
        'member_member_search',
        'member_member_add',
        'member_member_WalletAdjustment_add',
        'member_detail',
        'member_detail_search',
        'member_rank',
        'member_TagArrangement',
        'BusinessReport_report',
        'BusinessReport_report_filter',
        'BusinessReport_report_export',
        'BusinessReport_AgentReport_unfold',
        'BusinessReport_AgentReport_account',
        'BusinessReport_MemberReport_unfold',
        'BusinessReport_MemberReport_account',
        'BusinessReport_report_detail',
        'report_GameReport',
        'report_WalletReport',
        'report_TransactionReport',
        'report_BetReport',
        'game_MaintenanceTimeSetting',
        'promotion_RankList',
        'promotion_library',
        'promotion_library_download',
        // 'system_permissions',
        // 'system_permissions_add',
        // 'system_permissions_see',
        'system_VIP',
        'system_share',
        'system_WageringRequirement',
        'system_maintenance',
        'PersonalSetting_AccountInformation',
        'PersonalSetting_AccountInformation_EditPassword',
        'PersonalSetting_AccountInformation_Alias',
        'PersonalSetting_AccountInformation_Layer',
        'PersonalSetting_AccountInformation_LoginRecord_other',
        'PersonalSetting_AccountInformation_OperationRecord_other',
        'PersonalSetting_AccountInformation_SelectPhoto',
        'PersonalSetting_AccountInformation_save',
        'PersonalSetting_PointTransferRecord',
        'PersonalSetting_QuotaRecord',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->taskStart("PermissionSeeder");

        collect(self::permission)->map(fn ($item) => $this->execute($item));

        // 任務結束時間
        $this->taskEnd();
    }

    public function execute($value)
    {
        $id = Permission::create([
            'name' => $value,
            'guard_name' => 'user'
        ])->id;

        $this->giveRolePermission('admin', $value);

        if (!in_array($value, self::ownerExceptPermission))
            $this->giveRolePermission('owner', $value);

        if (!in_array($value, self::customerServiceSupervisorExceptPermission))
            $this->giveRolePermission('customer-service-supervisor', $value);

        if (!in_array($value, array_merge(self::customerServiceSupervisorExceptPermission, self::customerServiceExceptFewPermission)))
            $this->giveRolePermission('customer-service', $value);

        if (in_array($value, self::agentHasPermission))
            $this->giveRolePermission('agent', $value);

        $this->permissionLevelHandle($id, $value);
    }

    protected function permissionLevelHandle($id, $permission)
    {
        $stringAry = explode('_', $permission);

        $parentId = null;

        $parent = null;

        if (count($stringAry) == 3) {
            $parentId = Permission::where('name', sprintf('%s_%s', $stringAry[0], $stringAry[1]))->get()->pluck('id')->pop();

            $parent = PermissionLevel::find($parentId) ?? null;
        }

        if (count($stringAry) == 4) {
            $parentId = Permission::where('name', sprintf('%s_%s_%s', $stringAry[0], $stringAry[1], $stringAry[2]))->get()->pluck('id')->pop();

            $parent = PermissionLevel::find($parentId) ?? null;
        }

        $permissionLevel = new PermissionLevel;

        $permissionLevel->permission_id = $id;

        if ($parent) {
            $permissionLevel->parent_id = $parentId;

            $permissionLevel->appendToNode($parent)->save();
        } else {
            $permissionLevel->save();
        }
    }
}
