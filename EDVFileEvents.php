<?php
namespace EDV\FileBundle;

final class EDVFileEvents
{
  /**
   * File updated event fires when file is uploaded after it is persisted
   */
  const FILE_UPDATED_EVENT = 'ed_file.file_updated';

  /**
   * File removed event fires when file is removed
   */
  const FILE_REMOVED_EVENT = 'ed_file.file_removed';

  const FILE_UPLOADED_EVENT = 'ed_file.file_updated';
}