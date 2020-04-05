#!/bin/bash

SOURCE="${BASH_SOURCE[0]}"
while [ -h "$SOURCE" ]; do # resolve $SOURCE until the file is no longer a symlink
  DIR="$( cd -P "$( dirname "$SOURCE" )" && pwd )"
  SOURCE="$(readlink "$SOURCE")"
  [[ $SOURCE != /* ]] && SOURCE="$DIR/$SOURCE" # if $SOURCE was a relative symlink, we need to resolve it relative to the path where the symlink file was located
done
DIR="$( cd -P "$( dirname "$SOURCE" )" && pwd )"

# JavaFX charts rendering requires this to be set.
export SWT_GTK3=0

# Without the following line, sliders are not visible in Ubuntu 12.04
# (see <https://bugs.eclipse.org/bugs/show_bug.cgi?id=368929>)
export LIBOVERLAY_SCROLLBAR=0

$DIR/../jre/bin/java -Dprism.order=sw -jar $DIR/../lib/data-man-mongodb-core-*.jar
