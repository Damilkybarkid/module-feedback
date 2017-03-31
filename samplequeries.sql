-- Get first and second name for a single student
select SPR_FNM1, SPR_SURN
	from INS_SPR
	where SPR_CODE='50200036';

-- Find modules for one student
select CAM_SMO.MOD_CODE, MOD_NAME, INS_MOD.PRS_CODE, PRS_FNM1, PRS_SURN
        from CAM_SMO join INS_MOD ON (CAM_SMO.MOD_CODE = INS_MOD.MOD_CODE)
                left join INS_PRS ON (INS_MOD.PRS_CODE = INS_PRS.PRS_CODE)
        where SPR_CODE='50200036' AND AYR_CODE='2016/7' AND PSL_CODE='TR1';

-- Get the question
select QUE_CODE, CAT_CODE, QUE_NAME, QUE_TEXT
        from INS_QUE;

