##  Process this file with automake to create Makefile.in

includedir =
pkgconfigdir =

BUILD_DATE=$(shell date +%F)


bin_PROGRAMS =dcmd_agent_info dcmd_invalid_agent dcmd_running_opr dcmd_subtask_output dcmd_task_cmd dcmd_exec_opr dcmd_opr_script dcmd_running_subtask dcmd_subtask_process dcmd_task_script dcmd_exec_dup_opr dcmd_script dcmd_script_list

dcmd_task_script_CPPFLAGS = -D_BUILD_DATE="$(BUILD_DATE)" -W -Wall
dcmd_task_script_SOURCES = dcmd_task_script.cc\
                ../cmn/dcmd_def.cc\
                ../cmn/dcmd_cmn.pb.cc\
                ../cmn/dcmd_ui.pb.cc\
                ../cmn/dcmd_tss.cc
dcmd_task_script_LDADD=
dcmd_task_script_LDFLAGS=

dcmd_subtask_process_CPPFLAGS = -D_BUILD_DATE="$(BUILD_DATE)" -W -Wall
dcmd_subtask_process_SOURCES = dcmd_subtask_process.cc\
                ../cmn/dcmd_def.cc\
                ../cmn/dcmd_cmn.pb.cc\
                ../cmn/dcmd_ui.pb.cc\
                ../cmn/dcmd_tss.cc
dcmd_subtask_process_LDADD=
dcmd_subtask_process_LDFLAGS=

dcmd_running_subtask_CPPFLAGS = -D_BUILD_DATE="$(BUILD_DATE)" -W -Wall
dcmd_running_subtask_SOURCES = dcmd_running_subtask.cc\
                ../cmn/dcmd_def.cc\
                ../cmn/dcmd_cmn.pb.cc\
                ../cmn/dcmd_ui.pb.cc\
                ../cmn/dcmd_tss.cc
dcmd_running_subtask_LDADD=
dcmd_running_subtask_LDFLAGS=

dcmd_opr_script_CPPFLAGS = -D_BUILD_DATE="$(BUILD_DATE)" -W -Wall
dcmd_opr_script_SOURCES = dcmd_opr_script.cc\
                ../cmn/dcmd_def.cc\
                ../cmn/dcmd_cmn.pb.cc\
                ../cmn/dcmd_ui.pb.cc\
                ../cmn/dcmd_tss.cc
dcmd_opr_script_LDADD=
dcmd_opr_script_LDFLAGS=

dcmd_task_cmd_CPPFLAGS = -D_BUILD_DATE="$(BUILD_DATE)" -W -Wall
dcmd_task_cmd_SOURCES = dcmd_task_cmd.cc\
                ../cmn/dcmd_def.cc\
                ../cmn/dcmd_cmn.pb.cc\
                ../cmn/dcmd_ui.pb.cc\
                ../cmn/dcmd_tss.cc
dcmd_task_cmd_LDADD=
dcmd_task_cmd_LDFLAGS=

dcmd_exec_opr_CPPFLAGS = -D_BUILD_DATE="$(BUILD_DATE)" -W -Wall
dcmd_exec_opr_SOURCES = dcmd_exec_opr.cc\
                ../cmn/dcmd_def.cc\
                ../cmn/dcmd_cmn.pb.cc\
                ../cmn/dcmd_ui.pb.cc\
                ../cmn/dcmd_tss.cc
dcmd_exec_opr_LDADD=
dcmd_exec_opr_LDFLAGS=

dcmd_exec_dup_opr_CPPFLAGS = -D_BUILD_DATE="$(BUILD_DATE)" -W -Wall
dcmd_exec_dup_opr_SOURCES = dcmd_exec_dup_opr.cc\
		../cmn/dcmd_def.cc\
		../cmn/dcmd_cmn.pb.cc\
		../cmn/dcmd_ui.pb.cc\
		../cmn/dcmd_tss.cc
dcmd_exec_dup_opr_LDADD=
dcmd_exec_dup_opr_LDFLAGS=

dcmd_agent_info_CPPFLAGS = -D_BUILD_DATE="$(BUILD_DATE)" -W -Wall
dcmd_agent_info_SOURCES = dcmd_agent_info.cc\
		../cmn/dcmd_def.cc\
		../cmn/dcmd_cmn.pb.cc\
                ../cmn/dcmd_ui.pb.cc\
		../cmn/dcmd_tss.cc
dcmd_agent_info_LDADD=
dcmd_agent_info_LDFLAGS=

dcmd_invalid_agent_CPPFLAGS = -D_BUILD_DATE="$(BUILD_DATE)" -W -Wall
dcmd_invalid_agent_SOURCES = dcmd_invalid_agent.cc\
                ../cmn/dcmd_def.cc\
                ../cmn/dcmd_cmn.pb.cc\
                ../cmn/dcmd_ui.pb.cc\
                ../cmn/dcmd_tss.cc
dcmd_invalid_agent_LDADD=
dcmd_invalid_agent_LDFLAGS=


dcmd_running_opr_CPPFLAGS = -D_BUILD_DATE="$(BUILD_DATE)" -W -Wall
dcmd_running_opr_SOURCES = dcmd_running_opr.cc\
                ../cmn/dcmd_def.cc\
                ../cmn/dcmd_cmn.pb.cc\
                ../cmn/dcmd_ui.pb.cc\
                ../cmn/dcmd_tss.cc
dcmd_running_opr_LDADD=
dcmd_running_opr_LDFLAGS=

dcmd_subtask_output_CPPFLAGS = -D_BUILD_DATE="$(BUILD_DATE)" -W -Wall
dcmd_subtask_output_SOURCES = dcmd_subtask_output.cc\
                ../cmn/dcmd_def.cc\
                ../cmn/dcmd_cmn.pb.cc\
                ../cmn/dcmd_ui.pb.cc\
                ../cmn/dcmd_tss.cc
dcmd_subtask_output_LDADD=
dcmd_subtask_output_LDFLAGS=

dcmd_script_CPPFLAGS = -D_BUILD_DATE="$(BUILD_DATE)" -W -Wall
dcmd_script_SOURCES = dcmd_script.cc\
		../cmn/dcmd_def.cc\
		../cmn/dcmd_cmn.pb.cc\
		../cmn/dcmd_ui.pb.cc\
		../cmn/dcmd_tss.cc
dcmd_script_LDADD=
dcmd_script_LDFLAGS=

dcmd_script_list_CPPFLAGS = -D_BUILD_DATE="$(BUILD_DATE)" -W -Wall
dcmd_script_list_SOURCES = dcmd_script_list.cc\
		../cmn/dcmd_def.cc\
		../cmn/dcmd_cmn.pb.cc\
		../cmn/dcmd_ui.pb.cc\
		../cmn/dcmd_tss.cc
dcmd_script_list_LDADD=
dcmd_script_list_LDFLAGS=

LIBS=-lcwxnet -lcwxcmn -lprotobuf -lpthread -lz -ldl -lrt 
INCLUDES=-I@cwinux_dir@/cmn -I@cwinux_dir@/net -I@protobuf_dir@ -I. -I../cmn

## Clean up template repositories, etc.
clean-local:
	-rm -f *~ *.bak *.rpo *.sym lib*.*_pure_* core core.*
