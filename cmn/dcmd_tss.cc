#include "dcmd_tss.h"

namespace dcmd {

DcmdTss::~DcmdTss() {
  if (data_buf_) delete []data_buf_;
}

int DcmdTss::Init(){
  if (data_buf_) delete [] data_buf_;
  data_buf_ = NULL;
  data_buf_len_ = 0;
  return 0;
}

bool DcmdTss::ReadFile(char const* filename, string& file_content,
  string& err_msg)
{
  if (!CwxFile::isFile(filename)) {
    CwxCommon::snprintf(m_szBuf2K, kDcmd2kBufLen, "File doesn't exist. file:%s", filename);
    err_msg = m_szBuf2K;
    return false;
  }
  FILE* fd = fopen(filename, "rb");
  off_t file_size = CwxFile::getFileSize(filename);
  if (-1 == file_size){
    CwxCommon::snprintf(m_szBuf2K, kDcmd2kBufLen,
      "Failure to get file size, file:%s, errno=%d", filename, errno);
    err_msg = m_szBuf2K;
    return false;
  }
  if (!fd){
    CwxCommon::snprintf(m_szBuf2K, kDcmd2kBufLen,
      "Failure to open file:%s, errno=%d", filename, errno);
    err_msg = m_szBuf2K;
    return false;
  }
  char* szBuf = GetBuf(file_size);
  if ((size_t)file_size != fread(szBuf, 1, file_size, fd)){
    CwxCommon::snprintf(m_szBuf2K, kDcmd2kBufLen,
      "Failure to read file:%s, errno=%d", filename, errno);
    err_msg = m_szBuf2K;
    return false;
  }
  file_content.assign(szBuf, file_size);
  return true;
}
}

