##  Process this file with automake to create Makefile.in

includedir =
pkgconfigdir =

BUILD_DATE=$(shell date +%F)


bin_PROGRAMS = dcmd_center
dcmd_center_CPPFLAGS = -D_BUILD_DATE="$(BUILD_DATE)" -W -Wall
dcmd_center_SOURCES =dcmd_center_agent_mgr.cc\
		dcmd_center_h4_admin.cc\
		dcmd_center_opr_task.cc\
		dcmd_center_watch_mgr.cc\
		dcmd_center_app.cc\
		dcmd_center_h4_agent_opr.cc\
		dcmd_center_run_opr_task.cc\
		dcmd_mysql.cc\
		dcmd_center.cc\
		dcmd_center_h4_agent_task.cc\
		dcmd_center_run_subtask_task.cc\
		dcmd_xml_parse.cc\
		dcmd_center_config.cc\
		dcmd_center_h4_check.cc\
		dcmd_center_subtask_output_task.cc\
		dcmd_center_def.cc\
		dcmd_center_opr_cache.cc\
		dcmd_center_task_mgr.cc\
		../cmn/dcmd_def.cc\
		../cmn/dcmd_cmn.pb.cc\
		../cmn/dcmd_ui.pb.cc\
		../cmn/dcmd_agent.pb.cc\
		../cmn/dcmd_tss.cc
dcmd_center_LDADD=
dcmd_center_LDFLAGS=
LIBS=-lcwxapp -lcwxnet -lcwxcmn -lprotobuf -lmysqlclient -lexpat -lz -lpthread -ldl -lrt 
INCLUDES=-I@cwinux_dir@/app -I@cwinux_dir@/cmn -I@cwinux_dir@/net -I@protobuf_dir@ -I@mysql_dir@ -I@expat_dir@ -I. -I../cmn

## Clean up template repositories, etc.
clean-local:
	-rm -f *~ *.bak *.rpo *.sym lib*.*_pure_* core core.*
