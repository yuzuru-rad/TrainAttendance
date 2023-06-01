import nfc
import binascii
from nfc.clf import RemoteTarget

try: 
    clf = nfc.ContactlessFrontend('usb')
    tag = clf.connect(rdwr={
        'on-connect': lambda tag: False
    })
    idm = binascii.hexlify(tag.idm).decode()
    print(idm)   

except AttributeError:
    print("error")
    exit()