##  Process this file with automake to create Makefile.in

includedir =
pkgconfigdir =

BUILD_DATE=$(shell date +%F)


bin_PROGRAMS = dcmd_agent
dcmd_agent_CPPFLAGS = -D_BUILD_DATE="$(BUILD_DATE)" -W -Wall
dcmd_agent_SOURCES = dcmd_agent_app.cc\
		dcmd_agent.cc\
		dcmd_agent_config.cc\
		dcmd_agent_def.cc\
		dcmd_process.cc\
		../cmn/dcmd_def.cc\
		../cmn/dcmd_cmn.pb.cc\
		../cmn/dcmd_agent.pb.cc\
		../cmn/dcmd_tss.cc
dcmd_agent_LDADD=
dcmd_agent_LDFLAGS=
LIBS=-lcwxapp -lcwxnet -lcwxcmn -lprotobuf -lpthread -lz -ldl -lrt 
INCLUDES=-I@cwinux_dir@/app -I@cwinux_dir@/cmn -I@cwinux_dir@/net -I@protobuf_dir@ -I. -I../cmn

## Clean up template repositories, etc.
clean-local:
	-rm -f *~ *.bak *.rpo *.sym lib*.*_pure_* core core.*
