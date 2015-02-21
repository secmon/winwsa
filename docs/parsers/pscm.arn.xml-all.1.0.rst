
Autoruns-XML-All. 1.0 (pscm.arn.xml-all.1.0)
============================================

Общая информация
++++++++++++++++

Провайдер
+++++++++

    ``location``
      Источник "автозагрузки" показателя. Это ключ реестра, в котором располагается показатель (при этом, используются 
      сокращения: HKLM, HKCR, HKCU), путь к INI-файлу, Task Scheduler для заданий планировщика. 
      Например: HKLM\System\CurrentControlSet\Control\Session Manager\BootExecute
      
    ``itemname``
      Имя показателя. В основном человекочитаемая интерпретация показателя. Например:
      autocheck autochk /k:C /k:D *, %systemroot%\system32\scext.dll, \avast! Emergency Update (имя задания)
