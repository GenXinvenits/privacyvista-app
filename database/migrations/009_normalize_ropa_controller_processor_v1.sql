-- Normalize the independently versioned ROPA templates against the supplied Controller and Processor specifications.
-- This migration creates v2 only when v1 has already been published by migration 008.
-- Existing records keep their original form_version_id and therefore remain immutable.

INSERT INTO form_template_versions (form_template_id, version_number, definition, status, change_summary, created_by)
SELECT ft.id, 2,
JSON_OBJECT('sections', JSON_ARRAY(
  JSON_OBJECT('title','Record & organisation','fields',JSON_ARRAY(
    JSON_OBJECT('key','record_id','label','Record ID','type','auto'),
    JSON_OBJECT('key','client_id','label','Organisation / Client','type','client','required',true),
    JSON_OBJECT('key','department','label','Department / Business / Organisational Unit','type','text'),
    JSON_OBJECT('key','project','label','Project name / Processing Context','type','text'),
    JSON_OBJECT('key','process_name','label','Process / Activity Name','type','text','required',true),
    JSON_OBJECT('key','status','label','Processing Status','type','select','options',JSON_ARRAY('active','inactive')),
    JSON_OBJECT('key','ropa_role','label','Processing Role','type','select','required',true,'options',JSON_ARRAY('controller','joint_controller')),
    JSON_OBJECT('key','legal_frameworks','label','Applicable Legal Framework','type','multiselect','options',JSON_ARRAY('GDPR','ISO 27701','CCPA','DPDPA','Other')),
    JSON_OBJECT('key','process_owner','label','Process Owner / Contact','type','text'),
    JSON_OBJECT('key','dpo','label','Data Protection Officer Name / Contact','type','text'),
    JSON_OBJECT('key','eu_representative','label','EU Representative Name (if Applicable)','type','text')
  )),
  JSON_OBJECT('title','Processing details','fields',JSON_ARRAY(
    JSON_OBJECT('key','record_updated_date','label','Date of Entry / Update','type','auto'),
    JSON_OBJECT('key','lawful_basis','label','Lawful Basis for Processing','type','multiselect','options',JSON_ARRAY('Consent','Legitimate Uses (Processing without Consent)','Contract','Legal Obligation','Public Interest','Vital Interests')),
    JSON_OBJECT('key','purpose','label','Purpose of Processing','type','textarea'),
    JSON_OBJECT('key','data_subject_categories','label','Data Subject Categories','type','multiselect','options',JSON_ARRAY('Employees (Personnel)','Customers/Clients','Job Applicants/Candidates','Website/App Users','Contractors/Vendors/Suppliers','Shareholders/Investors','Children data','Person with Disability','Business Contacts','Other')),
    JSON_OBJECT('key','personal_data_categories','label','Personal Data Categories','type','multiselect','options',JSON_ARRAY('PII','SPI','Address','IP Address','Account Number','Date of Birth','Physical Address','Financial Data','Health Data','Biometric Data','Other')),
    JSON_OBJECT('key','special_data','label','Special Category / Sensitive Data','type','multiselect','options',JSON_ARRAY('Genetic Data','Biometric Data','Payment Card Data','Financial Account Credentials','Government IDs','Precise Geolocation','Racial/Ethnic Origin','Religious/Philosophical Beliefs','Political Opinions','Trade Union Membership','Employment Data containing Sensitive Information','Identity Documentation containing Sensitive Information','Sex Life Data','Sexual Orientation Data','Criminal History','Other')),
    JSON_OBJECT('key','large_scale_processing','label','Large Scale Processing','type','select','options',JSON_ARRAY('yes','no')),
    JSON_OBJECT('key','data_source','label','Source of Personal Data','type','multiselect','options',JSON_ARRAY('Directly Collected from Data Subject','Indirect (web cookies, third parties, integrations, publicly available sources, server logs)','Inventory','Other')),
    JSON_OBJECT('key','privacy_notice_reference','label','Reference to Data Privacy Notice Given (Policy Name or ID)','type','text')
  )),
  JSON_OBJECT('title','Systems, storage & lifecycle','fields',JSON_ARRAY(
    JSON_OBJECT('key','data_format','label','Data Format','type','select','options',JSON_ARRAY('Paper','Digital','Both')),
    JSON_OBJECT('key','hosting_system','label','Primary Hosting System / Asset','type','text'),
    JSON_OBJECT('key','processing_environment','label','Processing Environment','type','select','options',JSON_ARRAY('On-Premise','On Cloud','Hybrid','Other')),
    JSON_OBJECT('key','storage_countries','label','Data Storage Location/s','type','text'),
    JSON_OBJECT('key','dpdpa_whitelist_status','label','Country Whitelisted as per DPDPA List','type','select','options',JSON_ARRAY('yes','no','not_assessed')),
    JSON_OBJECT('key','data_volume','label','Data Volume','type','select','options',JSON_ARRAY('under_100000','100000_to_6_million','over_6_million','systematic_public_monitoring','other')),
    JSON_OBJECT('key','processing_frequency','label','Processing Frequency','type','select','options',JSON_ARRAY('annually','quarterly','bi_annually','monthly','regularly_systematically','on_significant_change','on_risk_change','on_consent_or_contract_expiry','as_needed','one_time')),
    JSON_OBJECT('key','retention_period','label','Data Retention Period / Storage Limitation','type','text'),
    JSON_OBJECT('key','sale_or_sharing','label','Sale / Sharing of Personal Information','type','select','options',JSON_ARRAY('yes','no','not_applicable')),
    JSON_OBJECT('key','data_sharing_scope','label','Data Sharing','type','select','options',JSON_ARRAY('internal','external','both','none'))
  )),
  JSON_OBJECT('title','Recipients, transfers & safeguards','fields',JSON_ARRAY(
    JSON_OBJECT('key','internal_recipients','label','Internal Recipients','type','textarea'),
    JSON_OBJECT('key','external_recipients','label','External Recipients','type','textarea'),
    JSON_OBJECT('key','processors','label','Processors / Vendors / Independent Controller / Joint Controller / Sub-Processors','type','textarea'),
    JSON_OBJECT('key','international_transfer','label','Cross-Border Transfer / International Transfer','type','select','options',JSON_ARRAY('yes','no')),
    JSON_OBJECT('key','transfer_safeguards','label','Data Transfer Safeguard Mechanism','type','multiselect','options',JSON_ARRAY('SCC','Binding Corporate Rules','Data Privacy Framework','Adequacy Decision','DPDPA Section 16','Other'))
  )),
  JSON_OBJECT('title','Security, DPIA & privacy governance','fields',JSON_ARRAY(
    JSON_OBJECT('key','security_measures','label','Security Measures (Technical & Organizational Measures)','type','textarea'),
    JSON_OBJECT('key','privacy_by_design','label','Data Privacy by Design & Default Applicable','type','select','options',JSON_ARRAY('yes','no')),
    JSON_OBJECT('key','dpia_applicability','label','Data Protection Impact Assessment Applicable','type','select','options',JSON_ARRAY('yes','no')),
    JSON_OBJECT('key','dpia_triggers','label','DPIA Trigger(s)','type','multiselect','options',JSON_ARRAY('Large Amount of Data','Processing Sensitive Personal Information','Systematic Profiling','Public Monitoring','New Technologies','Data Matching','Invisible Processing','Vulnerable Subjects','Biometric Identification','Genetic Data Processing','Other')),
    JSON_OBJECT('key','linked_dpia','label','Linked DPIA Reference ID','type','text'),
    JSON_OBJECT('key','lia_reference','label','Legitimate Interest Assessment Reference','type','text'),
    JSON_OBJECT('key','dpa_reference','label','Data Processing Agreement Reference','type','text'),
    JSON_OBJECT('key','joint_controller_agreement','label','Joint Controller Arrangement','type','text'),
    JSON_OBJECT('key','third_party_risk_assessment','label','Third-Party Risk Assessment','type','text'),
    JSON_OBJECT('key','privacy_risk_rating','label','Privacy Risk Rating','type','select','options',JSON_ARRAY('low','medium','high','critical')),
    JSON_OBJECT('key','consent_mechanism','label','Consent Mechanism Implemented','type','select','options',JSON_ARRAY('yes','no','not_applicable'))
  )),
  JSON_OBJECT('title','Rights, breach, disposal & review','fields',JSON_ARRAY(
    JSON_OBJECT('key','adm_profiling','label','Automated Decision-Making / Profiling','type','select','options',JSON_ARRAY('yes','no')),
    JSON_OBJECT('key','adm_profiling_details','label','ADM / Profiling Details','type','textarea'),
    JSON_OBJECT('key','dsar_mechanism','label','Data Subject Rights Management Mechanism','type','multiselect','options',JSON_ARRAY('All Rights','Right to be Informed','Right of Consumer Access','Right to Rectification / Correction','Right to Erasure / Deletion','Right to Data Portability','Right to Object / Opt-Out','Right to Restrict Processing','Right to Limit Sensitive Data Use','Right to Non-Automated Decision Making','Right to Withdraw Consent','Right to Grievance Redressal','Right to Nominate','Right to Accounting of Disclosures','Right to Request Restrictions')),
    JSON_OBJECT('key','breach_management','label','Personal Data Breach Management','type','select','options',JSON_ARRAY('yes','no')),
    JSON_OBJECT('key','disposal_date','label','Data Disposal / Deletion Date or Expected Date','type','date'),
    JSON_OBJECT('key','disposal_method','label','Data Disposal / Deletion Method','type','textarea'),
    JSON_OBJECT('key','retention_disposal_policy_reference','label','Data Retention & Disposal Policy Reference','type','text'),
    JSON_OBJECT('key','data_subject_requests','label','Record of Data Subject Requests / Complaints','type','textarea'),
    JSON_OBJECT('key','last_audit_date','label','Last Audit / Review Date','type','date'),
    JSON_OBJECT('key','next_review_date','label','Next Scheduled Review Date','type','date'),
    JSON_OBJECT('key','approver','label','Approver (Management / DPO)','type','text'),
    JSON_OBJECT('key','remarks','label','Remarks / Notes','type','textarea')
  ))
)), 'published', 'Normalize Controller ROPA to supplied template specification', NULL
FROM form_templates ft
WHERE ft.slug='ropa-controller'
AND NOT EXISTS (SELECT 1 FROM form_template_versions v WHERE v.form_template_id=ft.id AND v.version_number=2);

INSERT INTO form_template_versions (form_template_id, version_number, definition, status, change_summary, created_by)
SELECT ft.id, 2,
JSON_OBJECT('sections', JSON_ARRAY(
  JSON_OBJECT('title','Record & processor organisation','fields',JSON_ARRAY(
    JSON_OBJECT('key','record_id','label','Record ID','type','auto'),
    JSON_OBJECT('key','client_id','label','Organisation / Client','type','client','required',true),
    JSON_OBJECT('key','department','label','Department / Business / Organisational Unit','type','text'),
    JSON_OBJECT('key','project','label','Project name / Processing Context','type','text'),
    JSON_OBJECT('key','process_name','label','Process / Activity Name','type','text','required',true),
    JSON_OBJECT('key','status','label','Processing Status','type','select','options',JSON_ARRAY('active','inactive')),
    JSON_OBJECT('key','legal_frameworks','label','Applicable Legal Framework','type','multiselect','options',JSON_ARRAY('GDPR','ISO 27701','CCPA','DPDPA','Other')),
    JSON_OBJECT('key','process_owner','label','Processor Process Owner / Contact','type','text'),
    JSON_OBJECT('key','dpo','label','Processor Data Protection Officer Name / Contact','type','text'),
    JSON_OBJECT('key','record_updated_date','label','Date of Entry / Update','type','auto')
  )),
  JSON_OBJECT('title','Controller instructions & processing services','fields',JSON_ARRAY(
    JSON_OBJECT('key','controller_name','label','Controller Name','type','text','required',true),
    JSON_OBJECT('key','processing_services','label','Processing Services','type','multiselect','options',JSON_ARRAY('Cloud hosting / infrastructure','SaaS application services','Data storage & backup','Managed IT services','Customer support processing','CRM processing','Payroll processing','HR administration','Recruitment','Background verification','Payment processing','Billing / invoicing','Marketing services','Email / SMS services','Website & analytics','Data analytics','AI / machine learning','Security monitoring / SOC','Vulnerability management','Incident response','Document management','Other')),
    JSON_OBJECT('key','purpose','label','Purpose of Processing as Instructed by Controller','type','textarea'),
    JSON_OBJECT('key','lawful_basis','label','Lawful Basis Determined by Controller','type','multiselect','options',JSON_ARRAY('Consent','Legitimate Uses / Legitimate Interests','Contract','Legal Obligation','Public Interest','Vital Interests','Other')),
    JSON_OBJECT('key','processing_categories','label','Categories of Processing','type','multiselect','options',JSON_ARRAY('Collection','Recording','Storage','Organisation','Retrieval','Consultation','Use','Sharing','Archiving','Deletion')),
    JSON_OBJECT('key','privacy_notice_reference','label','Controller Privacy Notice Reference','type','text')
  )),
  JSON_OBJECT('title','Data handled, source & lifecycle','fields',JSON_ARRAY(
    JSON_OBJECT('key','data_subject_categories','label','Data Subject Categories','type','multiselect','options',JSON_ARRAY('Employees','Customers / Clients','Job Applicants / Candidates','Website / App Users','Contractors / Vendors / Suppliers','Shareholders / Investors','Children','Persons with Disabilities','Business Contacts','Other')),
    JSON_OBJECT('key','personal_data_categories','label','Personal Data Categories','type','multiselect','options',JSON_ARRAY('PII','Sensitive Personal Information','Contact Details','Financial Data','Account Data','Online Identifiers','Employment Data','Other')),
    JSON_OBJECT('key','special_data','label','Special / Sensitive Data','type','multiselect','options',JSON_ARRAY('Health Data','Genetic Data','Biometric Data','Payment-card Data','Financial Credentials','Government IDs','Precise Geolocation','Racial or Ethnic Origin','Religious Beliefs','Political Opinions','Trade-union Membership','Sex-life / Sexual-orientation Data','Criminal-history Data','Private Communications','Other')),
    JSON_OBJECT('key','data_source','label','Data Received From / Source of Personal Data','type','multiselect','options',JSON_ARRAY('Controller','Data Subject','Third Party','Integration','Publicly Available Source','Other')),
    JSON_OBJECT('key','data_format','label','Data Format','type','select','options',JSON_ARRAY('Paper','Digital','Both')),
    JSON_OBJECT('key','hosting_system','label','Primary Hosting System / Asset','type','text'),
    JSON_OBJECT('key','processing_environment','label','Processing Environment','type','select','options',JSON_ARRAY('On-Premise','On Cloud','Hybrid','Other')),
    JSON_OBJECT('key','storage_countries','label','Data Storage Location/s','type','text'),
    JSON_OBJECT('key','processing_frequency','label','Processing Frequency','type','select','options',JSON_ARRAY('annually','quarterly','bi_annually','monthly','regularly_systematically','on_significant_change','on_risk_change','on_consent_or_contract_expiry','as_needed','one_time')),
    JSON_OBJECT('key','retention_period','label','Retention Period','type','text'),
    JSON_OBJECT('key','retention_controller_instruction','label','Retention / Data Return Instruction from Controller','type','textarea'),
    JSON_OBJECT('key','disposal_date','label','Disposal / Deletion / Return Date','type','date'),
    JSON_OBJECT('key','disposal_method','label','Disposal / Deletion Method','type','textarea')
  )),
  JSON_OBJECT('title','Sub-processors, transfers & agreements','fields',JSON_ARRAY(
    JSON_OBJECT('key','sale_or_sharing','label','Sale / Sharing of Personal Information','type','select','options',JSON_ARRAY('yes','no','not_applicable')),
    JSON_OBJECT('key','data_sharing_scope','label','Data Sharing Scope','type','select','options',JSON_ARRAY('internal','external','both','none')),
    JSON_OBJECT('key','subprocessors','label','Sub-processors / Third-party Processors','type','textarea'),
    JSON_OBJECT('key','international_transfer','label','Cross-Border / International Transfer','type','select','options',JSON_ARRAY('yes','no')),
    JSON_OBJECT('key','transfer_safeguards','label','Data Transfer Safeguard Mechanism','type','multiselect','options',JSON_ARRAY('SCC','Binding Corporate Rules','Data Privacy Framework','Adequacy Decision','DPDPA Section 16','Other')),
    JSON_OBJECT('key','dpa_reference','label','Data Processing Agreement Reference','type','text')
  )),
  JSON_OBJECT('title','Security, DPIA assistance, rights & breach','fields',JSON_ARRAY(
    JSON_OBJECT('key','security_measures','label','Security Measures Implemented to Controller Instructions','type','textarea'),
    JSON_OBJECT('key','security_per_controller_instruction','label','Security Measures Implemented According to Controller Instructions','type','select','options',JSON_ARRAY('yes','no','partially','not_assessed')),
    JSON_OBJECT('key','dpia_assistance','label','Processor Assists Controller with DPIA','type','select','options',JSON_ARRAY('yes','no','not_applicable')),
    JSON_OBJECT('key','dsar_support','label','DSAR Support Process Established','type','select','options',JSON_ARRAY('yes','no','not_applicable')),
    JSON_OBJECT('key','data_subject_requests','label','Data Subject Requests / Complaints Record','type','textarea'),
    JSON_OBJECT('key','breach_management','label','Personal Data Breach / Incident Management','type','textarea'),
    JSON_OBJECT('key','privacy_risk_rating','label','Privacy Risk Rating','type','select','options',JSON_ARRAY('low','medium','high','critical')),
    JSON_OBJECT('key','last_audit_date','label','Last Audit / Review Date','type','date'),
    JSON_OBJECT('key','last_audit_performed_by','label','Last Audit Performed By','type','text'),
    JSON_OBJECT('key','next_review_date','label','Next Scheduled Review Date','type','date'),
    JSON_OBJECT('key','approver','label','Approver','type','text'),
    JSON_OBJECT('key','remarks','label','Remarks / Notes','type','textarea')
  ))
)), 'published', 'Normalize Processor ROPA to supplied template specification', NULL
FROM form_templates ft
WHERE ft.slug='ropa-processor'
AND NOT EXISTS (SELECT 1 FROM form_template_versions v WHERE v.form_template_id=ft.id AND v.version_number=2);

UPDATE form_templates ft
JOIN form_template_versions v ON v.form_template_id=ft.id
SET ft.active_version_id=v.id
WHERE ft.slug IN ('ropa-controller','ropa-processor')
  AND v.version_number=2
  AND v.status='published';
